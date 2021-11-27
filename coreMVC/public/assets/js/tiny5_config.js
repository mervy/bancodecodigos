 tinymce.init({
    selector: 'textarea',
     plugins: [
      'advlist autolink link image lists charmap preview hr anchor pagebreak',
       'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
       'table template paste help'
     ],
    toolbar: 'undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link linkbreak image | preview media fullpage | ' +
      'forecolor backcolor',
    menu: {
      favs: {title: 'My Favorites', items: 'code link image | searchreplace | '}
    },
    menubar: 'favs edit view insert format tools table', 
    image_advtab: true, 
    image_dimensions: false, //manipular o width e height via css  
    image_class_list: [
      { title: 'None', value: '' },
      { title: 'Responsive', value: 'img-fluid' },
      { title: 'Left', value: 'float-left' },
      { title: 'Right', value: 'float-right' },
      { title: 'Margin 3', value: 'm-3' },
      { title: 'Padding 3', value: 'p-3' }
    ]
  });