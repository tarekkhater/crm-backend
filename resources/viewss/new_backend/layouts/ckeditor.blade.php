<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/4/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '.textarea',
        menu: {
            file: { title: 'File', items: 'newdocument' },
            edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall' },
            insert: { title: 'Insert', items: 'link media | template hr' },
            view: { title: 'View', items: 'visualaid' },
            format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat' },
            table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' },
            tools: { title: 'Tools', items: 'spellchecker code' },
            myapp: { title: 'My Application', items: 'myapp1 myapp2 myapp3 myapp4  myapp5 myapp6 myapp7' }
        },
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'forecolor backcolor emoticons | codesample',
        image_advtab: true,
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        ],
        convert_urls: true,
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData,token;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('save_image') }}');
            token ='{{csrf_token()}}';
            xhr.setRequestHeader("X-CsRF-TOKEN",token)
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    console.log(xhr)
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                console.log(xhr)
                url= (xhr.responseText);
                console.log(url.split('<link')[0])
                //your validation with the responce goes here
                success(url.split('<link')[0]);
            };
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData)
        },
        setup: function (editor) {
            editor.addMenuItem('myapp1', {
                text: 'Data Loop',
                onclick: function () {
                    editor.insertContent('{LOOP:Data}');
                }
            });
            editor.addMenuItem('myapp2', {
                text: 'Collection Loop',
                onclick: function () {
                    editor.insertContent('{LOOP:Collection}');
                }
            });
            editor.addMenuItem('myapp4', {
                text: 'Process Loop',
                onclick: function () {
                    editor.insertContent('{LOOP:Process}');
                }
            });
            editor.addMenuItem('myapp5', {
                text: 'Server Name',
                onclick: function () {
                    editor.insertContent('{ServerName}');
                }
            });
            editor.addMenuItem('myapp6', {
                text: 'Email Group Name',
                onclick: function () {
                    editor.insertContent('{EmailGroupName}');
                }
            });
            editor.addMenuItem('myapp7', {
                text: 'Alert Group Name',
                onclick: function () {
                    editor.insertContent('{AlertGroupName}');
                }
            });
        }
    });
</script>



