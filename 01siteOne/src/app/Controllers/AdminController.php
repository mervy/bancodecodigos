<?php

namespace App\Controllers;

use Core\Container;
use Core\BaseController;
use Core\DataBase;
use Core\Redirect;
use Core\Session;
use Core\Helper;
use Core\UploadHelper;
use Core\Authenticate;

class AdminController extends BaseController
{
    use Authenticate;

    public $articles;
    public $authors;
    public $categories;
    public $news;

    public function __construct()
    {
        parent::__construct();

        $this->setSiteName("coreMVC 3.0", 'admin');

        $conn = DataBase::getDatabase();  //não esquecer do use Core\DataBase;
        $this->articles = Container::getModelEx("Article", $conn);
        $this->authors = Container::getModelEx("Author", $conn);
        $this->categories = Container::getModelEx("Category", $conn);
        $this->visitor = Container::getModelEx("Visitor", $conn);
        $this->news = Container::getModelEx("Newsletter", $conn);
        $this->helper = new Helper;
    }

    public function index($request)
    {
        $this->view->articles = $this->articles->data('AR.*, CA.name as caName, AU.name as auName')
            ->join('categories as CA', 'AR.categories_id = CA.id')
            ->join('authors as AU', 'AR.authors_id = AU.id')
            ->order('created DESC, updated DESC')
            ->allWithJoin('AR');

        $p = new Helper();
        $p->paginate($this->view->articles, 12, $request);
        $this->view->articles = $p->result;
        $this->view->contar = $p->contar;
        $this->view->atual = $p->atual;

        $this->renderView('admin/article/index', 'layout');
    }

    /**
     * 
     * @param type $page
     * Mostra a lista de cadastro e opções para criar, editar e deletar
     */
    public function show($page, $request)
    {
        switch ($page) {
            case "article":
                $this->view->articles = $this->articles->data('AR.*, CA.name as caName, AU.name as auName')
                    ->join('categories as CA', 'AR.categories_id = CA.id')
                    ->join('authors as AU', 'AR.authors_id = AU.id')
                    ->order('created DESC, updated DESC')
                    ->allWithJoin('AR');

                $p = new Helper();
                $p->paginate($this->view->articles, 12, $request);
                $this->view->articles = $p->result;
                $this->view->contar = $p->contar;
                $this->view->atual = $p->atual;

                $this->renderView('admin/article/index', 'layout');
                break;
            case "author":
                $this->view->authors = $this->authors->All();
                $this->renderView('admin/author/index', 'layout');
                break;
            case "category":
                $p = new Helper();
                $p->paginate($this->categories->All(), 5, $request);
                $this->view->categories = $p->result;
                $this->view->contar = $p->contar;
                $this->view->atual = $p->atual;

                $this->renderView('admin/category/index', 'layout');
                break;
            case "visitor":
                $dados = $this->visitor->data('articles_id, title, visitors.id as viId, ip, acessed_in')
                    ->join('articles', 'visitors.articles_id = articles.id')
                    ->order('viId ASC')
                    ->allWithJoin(null);

                $p = new Helper();
                $p->paginate($dados, 5, $request);
                $this->view->visitor = $p->result;
                $this->view->contar = $p->contar;
                $this->view->atual = $p->atual;

                $this->renderView('admin/visitor/index', 'layout');
                break;
            case "newsletter":
                $dados = $this->news->all();

                $p = new Helper();
                $p->paginate($dados, 25, $request);
                $this->view->news = $p->result;
                $this->view->contar = $p->contar;
                $this->view->atual = $p->atual;

                $this->renderView('admin/newsletter/index', 'layout');
                break;
        }
    }

    /**
     * 
     * @param type $page
     * Mostra apenas os formulários para inserção de novos dados
     */
    public function create($page)
    {
        switch ($page) {
            case "article":
                $this->setPageTitle("Create new article");
                $this->view->categories = $this->categories->All();
                $this->view->authors = $this->authors->All();
                $this->renderView('admin/article/create', 'layout');
                break;
            case "author":
                $this->setPageTitle("Create new author");
                $this->renderView('admin/author/create', 'layout');
                break;
            case "category":
                $this->setPageTitle("Create new category");
                $this->renderView('admin/category/create', 'layout');
                break;
        }
    }

    /**
     * 
     * @param type $page
     * @param type $request
     * Grava os dados $request (do formulário de create) no banco de dados
     */
    public function store($page, $request)
    {
        switch ($page) {
            case "article":
                $capa = !empty($_FILES['files']['name'][0]) ? $_FILES['files']['name'][0] : " ";
                $data = [
                    "title" => $request->post->title,
                    "image" => $capa,
                    "content" => $request->post->content,
                    "created" => $request->post->created,
                    "updated" => $request->post->created,
                    "authors_id" => $request->post->authors_id,
                    "categories_id" => $request->post->categories_id,
                ];
                if (!empty($_FILES['files']['name'][0])) {
                    $upload = new UploadHelper();
                    $upload->setFile($_FILES['files'])
                        //Com o helper, o titulo se transforma na pasta das imagens
                        ->setPath('assets/uploads/articles/' . $this->helper->urlSEO($request->post->title, '-'))
                        ->upload();
                }
                if ($this->articles->create($data)) {
                    return Redirect::route('/admin', [
                        'success' => ['Artigo inserido com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin', [
                        'errors' => ['Erro ao inserir artigo!']
                    ]);
                }

                break;
            case "author":
                $data = [
                    "name" => $request->post->name,
                    "email" => $request->post->email,
                    "nickname" => $request->post->nickname,
                    "password" => $request->post->password
                ];
                $data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT);

                if ($this->authors->create($data)) {
                    return Redirect::route('/admin/show/author', [
                        'success' => ['Autor inserido com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin/show/author', [
                        'errors' => ['Erro ao inserir autor!']
                    ]);
                }
                break;
            case "category":
                $data = [
                    "name" => trim($request->post->name),
                    "slug" => trim($request->post->slug),
                    "description" => $request->post->description
                ];

                if ($this->categories->create($data)) {
                    return Redirect::route('/admin/show/category', [
                        'success' => ['Categoria inserido com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin/show/author', [
                        'errors' => ['Erro ao inserir categoria!']
                    ]);
                }
                break;
        }
    }

    public function preview($id)
    {
        $this->view->article = $this->articles->data(['AR.*', 'CA.id as caId', 'CA.name as caName', 'CA.slug as caSlug', 'AU.id as auId', 'AU.name as auName'])
            ->join('categories CA', "AR.id = $id AND AR.categories_id = CA.id")
            ->join('authors as AU', 'AR.authors_id = AU.id')
            ->oneWithJoin('AR');

        $this->renderView('admin/article/preview', 'layout');
    }

    public function edit($page, $id)
    {
        switch ($page) {
            case "article":
                $this->view->article = $this->articles->data(['AR.*', 'CA.id as caId', 'CA.name as caName', 'CA.slug as caSlug', 'AU.id as auId', 'AU.name as auName'])
                    ->join('categories CA', "AR.id = $id AND AR.categories_id = CA.id")
                    ->join('authors as AU', 'AR.authors_id = AU.id')
                    ->oneWithJoin('AR');

                $this->view->authors = $this->authors->All();
                $this->view->categories = $this->categories->All();

                /**
                 * Preparar visualização de imagens do servidor
                 */
                $dir = 'assets/uploads/articles/' . $this->helper->urlSEO($this->view->article['title'], '-') . '/';
                if (is_dir($dir)) {
                    $this->view->img = scandir($dir);
                }

                $this->setPageTitle('Edit article - ' . $this->view->article['title']);
                $this->renderView('admin/article/edit', 'layout');
                break;
            case "author":
                $this->view->author = $this->authors->find($id);
                $this->setPageTitle('Edit author - ' . $this->view->author['name']);
                $this->renderView('admin/author/edit', 'layout');
                break;
            case "category":
                $this->view->category = $this->categories->find($id);
                $this->setPageTitle('Edit category - ' . $this->view->category['name']);
                $this->renderView('admin/category/edit', 'layout');
                break;
        }
    }

    public function update($page, $id, $request)
    {
        switch ($page) {
            case "article":
                $capa = !empty($_FILES['files']['name'][0]) ? $_FILES['files']['name'][0] : $request->post->image;
                $data = [
                    "title" => $request->post->title,
                    "image" => $capa,
                    "content" => $request->post->content,
                    "updated" => $request->post->updated,
                    "authors_id" => $request->post->authors_id,
                    "categories_id" => $request->post->categories_id,
                ];
                if (!empty($_FILES['files']['name'][0]) || !empty($_FILES['files']['name'][1])) {
                    $upload = new UploadHelper();
                    $upload->setFile($_FILES['files'])
                        //Com o helper, o titulo se transforma na pasta das imagens
                        ->setPath('assets/uploads/articles/' . $this->helper->urlSEO($request->post->title, '-'))
                        ->upload();
                }
                //Exclui múltiplas imagens                
                if (@$request->post->imagedel != null)
                    foreach ($request->post->imagedel as $imgdel) {
                        unlink($imgdel);
                    }

                if ($this->articles->update($data, $id)) {
                    return Redirect::route('/admin', [
                        'success' => ['Artigo atualizado com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin', [
                        'errors' => ['Erro ao atualizar!']
                    ]);
                }
                break;
            case "author":
                $data = [
                    "name" => $request->post->name,
                    "email" => $request->post->email,
                    "nickname" => $request->post->nickname,
                    "password" => $request->post->password
                ];
                $data['password'] = password_hash($request->post->password, PASSWORD_BCRYPT);

                if ($this->authors->update($data, $id)) {
                    return Redirect::route('/admin/show/author', [
                        'success' => ['Author atualizado com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin', [
                        'errors' => ['Erro ao atualizar author!']
                    ]);
                }
                break;
            case "category":
                $data = [
                    "name" => trim($request->post->name),
                    "slug" => trim($request->post->slug),
                    "description" => $request->post->description
                ];
                if ($this->categories->update($data, $id)) {
                    return Redirect::route('/admin/show/category', [
                        'success' => ['Category atualizado com sucesso!']
                    ]);
                } else {
                    return Redirect::route('/admin', [
                        'errors' => ['Erro ao atualizar category!']
                    ]);
                }
                break;
        }
    }

    public function approve($page, $id)
    {
        switch ($page) {
            case "articles":
                try {
                    $u = $this->articles->find($id);
                    if ($u['status'] == 1) {
                        $this->articles->update(['status' => 0], $id);
                    } else {
                        $this->articles->update(['status' => 1], $id);
                    }

                    return Redirect::route('/admin');
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
                break;
            case "authors":
                try {
                    $u = $this->authors->find($id);
                    if ($u['status'] == 1) {
                        $this->authors->update(['status' => 0], $id);                       
                    } else {
                        $this->authors->update(['status' => 1], $id);
                    }
                    return Redirect::route('/admin/show/author');
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
                break;
            case "categories":
                try {
                    $u = $this->categories->find($id);
                    if ($u['status'] == 1) {
                        $this->categories->update(['status' => 0], $id);
                    } else {
                        $this->categories->update(['status' => 1], $id);
                    }
                    return Redirect::route('/admin/show/category');
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
                break;
        }
    }

    public function delete($page, $id)
    {
        switch ($page) {
            case "article":
                $this->articles->delete($id);
                return Redirect::route('/admin');
                break;
            case "category":
                $this->categories->delete($id);
                return Redirect::route('/admin/show/category');
                break;
            case "author":
                $this->authors->delete($id);
                return Redirect::route('/admin/show/author');
                break;
            case "visitor":
                $this->visitor->delete($id);
                return Redirect::route('/admin/show/visitor');
                break;
            case "newsletter":
                $this->news->delete($id);
                return Redirect::route('/admin/show/newsletter');
                break;
        }
    }
}
