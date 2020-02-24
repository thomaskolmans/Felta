</html>

<head>
    <title>Felta | Article</title>
    <link href="/felta/stylesheets/all.css" rel="stylesheet">
    <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/felta/js/datepicker/jquery.datetimepicker.css">
    <link rel="icon" href="/felta/images/black.png" type="image/png" />
    <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
    <script src="/felta/js/jquery-1.11.3.min.js"></script>
    <script src="/felta/js/ckeditor/ckeditor.js"></script>
    <script src="/felta/js/datepicker/jquery.datetimepicker.min.js" type="text/javascript"></script>
    <script src="/felta/js/shop.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript">
        $(document).ready(function() {
            var active = document.location.pathname;
            var parts = active.split('/');
            var active_id = "main";

            if (active.indexOf("new") !== -1) {
                active_id = "new";
            } else if (active.indexOf("update") !== -1) {
                active_id = "update";
            }

            onePage("#" + active_id, null, null);
            var lastactive = "#" + active_id;
            $("a").on("click", function(e) {
                if ($(this).attr("href")[0] == "#") {
                    e.preventDefault();
                    var id = $(this).attr("href");
                    if (id == "#new") {
                        jQuery('#datetimepicker').datetimepicker({
                            format: 'd.m.Y H:i',
                            inline: true,
                            lang: 'en',
                            startDate: new Date()
                        });
                    }
                    onePage("#" + active_id, id, lastactive);
                    lastactive = id;
                }
            });

            $(".background").on("click", function() {
                closeImageEditor();
            });

            Sortable.create(document.getElementById('image-selector'), {
                'filter': '.add'
            });

            Sortable.create(document.getElementById('image-selector-update'), {
                'filter': '.add'
            });

            var contentEditor = CKEDITOR.replace("content");
            CKEDITOR.replace("update-content");
        });

        function onePage(first, id, lastactive) {
            if (lastactive != null) {
                $(lastactive).hide();
            }
            if (first)
                if (id != null) {
                    $(id).fadeIn().css("display", "inline-block");
                } else {
                    $(first).fadeIn().css("display", "inline-block");
                }
            if (first === "#update" || id === "#update") {
                jQuery('#datetimepicker_update').datetimepicker({
                    format: 'd.m.Y H:i',
                    inline: true,
                    lang: 'en',
                    startDate: Date.parse($("#datetimepicker_update").val())
                });
            }
            return;
        }
    </script>
</head>

<body>


    <?php

    use lib\post\blog\Blog;
    use lib\post\blog\Article;

    $blog = Blog::get($_GET["blog"]);
    ?>
    <include>Felta/parts/nav.tpl</include>
    <div class="main-wrapper">
        <div class="main dashboard container multi-page" id="main">
            <h1 class="no-padding-bottom">Articles</h1>
            <?php echo '<p>Articles from ' . $blog->getName() . '</p>'; ?>
            <div class="stats no-margin news relative">
                <a href="#new"><button class="add">Add article</button></a>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>updatedAt</th>
                        <th class="clear"></th>
                        <th class="clear"></th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $items = Article::allFromBlog($blog->getId(), 0, 100);
                    if ($items != null && count($items) > 0) {
                        foreach ($items as $item) {
                            echo "
                            <tr>
                                <td>" . $item->getTitle() . "</td>
                                <td>" . $item->getUpdatedAt()->format("d-m-Y H:m") . "</td>
                                <td><a href='/felta/blog/" . $blog->getId() . "/article/" . $item->getId() . "/update'><button>Edit</button></a></td>
                                <td onclick='return (function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        url: \"/felta/blog/delete/" . $item->getId() . "\",
                                        type: \"DELETE\",
                                        success: function(result) {
                                            window.location.reload();
                                        }
                                    });
                                })(event)'><div class='delete'></div></td>
                            </tr>
                        ";
                        }
                    } else {
                        echo "<tr> <td colspan='7'><i>No articles</i></td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="main dashboard multi-page" id="new">
            <h1>New article</h1>
            <form method="post" class="full" onsubmit="return (function(e){
                e.preventDefault();
                $.ajax({
                    url: '/felta/article',
                    type: 'POST',
                    data: $('#new form').serialize(),
                    success: function(result) {
                        window.location.reload();
                    }
                });
            })(event)">
                <?php echo '<input type="text" name="blog" value="' . $blog->getId() . '" style="display:none" /> '; ?>
                <div class="input-group">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="General" />
                </div>
                <div class="input-group">
                    <label>Author</label>
                    <input type="text" name="author" placeholder="Unknown" />
                </div>
                <div class="input-group">
                    <label>Image</label>
                    <ul class="image-selector" id="image-selector">
                        <li class="add" onclick="imageEditor(function() {
                            uploadImage(
                                $('#file')[0].files[0]
                            ).then((response) => {
                                json = JSON.parse(response);
                                $('#image-selector').prepend(
                                    '<li image-id=\'' + json.uid + '\'>' +
                                        '<span class=\'delete\' onclick=\'(function(e) { e.target.parentElement.remove(); })(event)\' />' +
                                        '<input type=\'hidden\' name=\'images[]\' value=\'' + json.url + '\' />' +
                                        '<img src=\'' + json.url + '\' />' +
                                    '</li>'
                                );

                                Sortable.create(document.getElementById('image-selector'), {
                                   'items': ':not(.add)'
                                });
                                closeImageEditor();
                            }); 
                        })"></li>
                    </ul>
                </div>
                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>
                <div class="input-group">
                    <label>Content</label>
                    <textarea name="body" id="content"></textarea>
                </div>
                <div class="input-group">
                    <label>Active</label>
                    <label class="switch">
                        <input type="checkbox" name="active" class="switcher" checked>
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="input-group">
                    <label>Active From</label>
                    <input type="text" id="datetimepicker" name="activeFrom" />
                </div>
                <div class="input-group right">
                    <?php echo '<a href="/felta/blog/' . $blog->getId() . '/article"><input type="button" value="Cancel"></a>'; ?>
                    <input type="submit" value="Create article">
                </div>
            </form>

        </div>
        <div class="main dashboard multi-page" id="update">
            <h1>Edit article</h1>
            <?php
            if (isset($_GET["id"])) {
            $article = Article::get($_GET["id"]);
            ?>
            <?php echo '<form method="post" class="full" onsubmit="return (function(e){
                    e.preventDefault();
                    $.ajax({
                        url: \'/felta/article\',
                        type: \'PUT\',
                        data: $(\'#update form\').serialize(),
                        success: function(result) {
                            window.location.replace(\'/felta/blog/' . $blog->getId() . '/article\');
                        }
                    });
                })(event)">'; ?>
            <?php echo '<input type="text" name="id" value="' . $article->getId() . '" style="display:none" /> '; ?>
            <?php echo '<input type="text" name="blog" value="' . $blog->getId() . '" style="display:none" /> '; ?>
            <div class="input-group">
                <label>Title</label>
                <?php echo '<input type="text" value="' . $article->getTitle() . '" name="title" placeholder="General" />'; ?>
            </div>
            <div class="input-group">
                <label>Author</label>
                <?php echo '<input type="text" value="' . $article->getAuthor() . '" name="author" placeholder="Unknown" />'; ?>
            </div>
            <div class="input-group">
                <label>Image</label>
                <ul class="image-selector" id="image-selector-update">
                    <?php
                        foreach($article->getImages() as $imageKey => $image){
                        echo '
                            <li>
                                <span class="delete"  onclick=\'(function(e) { e.target.parentElement.remove(); })(event)\' ></span>
                                <input type="hidden" name="images[]" value="'.$image->getUrl().'" /> 
                                <img src="'.$image->getUrl().'" />
                            </li>
                        ';
                        }
                    ?>
                    <li class="add" onclick="imageEditor(function() {
                        uploadImage(
                            $('#file')[0].files[0]
                        ).then((response) => {
                            json = JSON.parse(response);
                            $('#image-selector-update').prepend(
                                '<li image-id=\'' + json.uid + '\'>' +
                                    '<span class=\'delete\' onclick=\'(function(e) { e.target.parentElement.remove(); })(event)\' />' +
                                    '<input type=\'hidden\' name=\'images[]\' value=\'' + json.url + '\' />' +
                                    '<img src=\'' + json.url + '\' />' +
                                '</li>'
                            );

                            Sortable.create(document.getElementById('image-selector'), {
                                'items': ':not(.add)'
                            });
                            closeImageEditor();
                        }); 
                    })"></li>
                </ul>
            </div>
            <div class="input-group">
                <label>Description</label>
                <?php echo '<textarea name="description">' . $article->getDescription() . '</textarea>'; ?>
            </div>
            <div class="input-group">
                <label>Content</label>
                <?php echo '<textarea name="body" id="update-content">' . $article->getBody() . '</textarea>'; ?>
            </div>
            <div class="input-group">
                <label>Active</label>
                <label class="switch">
                    <?php if ($blog->getActive()) {
                        echo '<input type="checkbox" name="active" class="switcher" checked />';
                    } else {
                        echo '<input type="checkbox" name="active" class="switcher" />';
                    } ?>
                    <div class="slider round"></div>
                </label>
            </div>
            <div class="input-group">
                <label>Active From</label>
                <?php echo '<input type="text" value="' . $article->getAuthor() . '" id="datetimepicker_update" name="activeFrom" />'; ?>
            </div>
            <div class="input-group right">
                <?php echo '<a href="/felta/blog/' . $blog->getId() . '/article"><input type="button" value="Cancel"></a>'; ?>
                <input type="submit" value="Update article">
            </div>
            <?php echo '</form>';
            } ?>
        </div>
    </div>
    <div class="main">
        <section class="image_editor" id="image_editor">
            <div class="background"></div>
            <div class="editor">
                <div class="container" id="imageeditor">
                    <form method="post" class="select-image" enctype="multipart/form-data" onsubmit="">
                        <div class="imageid" id="imageid"></div>
                        <div class="box__input">
                            <svg class="box__icon" width="50" height="43" viewBox="0 0 50 43">
                                <path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path>
                            </svg>
                            <input class="box__file" type="file" id="file" accept="image/*" />
                            <label for="file"><strong>Choose a file</strong>
                                <span class="box__dragndrop"> or drag it here</span>.
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</body>

</html>