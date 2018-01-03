@extends('layouts.master')
<html>
    <head>
    <script
        src="http://code.jquery.com/jquery-1.12.4.js"
        integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
        crossorigin="anonymous">
    </script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
    <script>
         $(document).on('click', ".removeButton", function() { 
            var id = $(this).attr('id');
            var url='/remove-cart/'+id;
            $.ajax(url, {success: function (response) {}});
        });        
    </script>
    <script>
         $(document).on('click', ".addButton", function() { 
            var id = $(this).attr('id');
            var url='/add-to-cart/'+id;
            $.ajax(url, {success: function (response) {}});
        });        
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            function renderList(products) {
                html = [
                        '<tr><th><?= trans('messages.Photo') ?></th>',
                        '<th><?= trans('messages.Specification') ?></th>',
                        '<th><?= trans('messages.Add') ?></th>',
                        '</tr>'
                    ].join('');
                $.each(products, function (key, product) {
                    html += [
                        '<tr>',
                            '<td>',
                                '<img class ="image" src="public/photo/photo-' + product.id + '.jpg">',
                            '</td>',
                            '<td>',                       
                                '<p>Title : ' + product.title + ' <br/>', 
                                'Description : ' + product.description + '<br/>', 
                                'Price : ' + product.price + '</p>',
                            '</td>',
                            '<td>'].join('');
                            if(window.location.hash == '#cart') {
                                html += ['<button id="' + product.id + '" class="removeButton" ><?= trans('messages.Remove') ?></button>'].join('');
                            } else {
                                html += ['<button id="' + product.id + '" class="addButton" ><?= trans('messages.Add') ?></button>'].join('');
                            }
                            html += ['</td>',
                        '</tr>'                        
                    ].join('');
                });
                return html;
            }

            window.onhashchange = function () {
                $('.page').hide();

                if (window.location.hash == '#cart') {
                    $('.cart').show();

                    $.ajax('/shopping-cart', {
                        dataType: 'json',
                        success: function (response) {
                            $('.cart .list').html(renderList(response));
                        }
                    });
                } else {
                    if (window.location.hash == '#login') {
                        $('.login').show();
                    } else {
                        $('.index').show();
                        $.ajax('/ind', {
                            dataType: 'json',
                            success: function (response) {
                                $('.index .list').html(renderList(response));
                            }
                        });
                    }                    
                }
            }
            window.onhashchange();
        });
    </script>
    <script>
        $(document).on('click', ".checkoutButton", function() {             
            var uri='/checkout';
            var myValues = {
                Name : document.getElementById("name").value,
                Contact : document.getElementById("contact").value,
                Comments : document.getElementById("comments").value
            }
            $.ajax({
                method: 'POST',
                url: uri,
                data: myValues,
                dataType: "json",
                success: function(resultData) { alert("Save Complete") },
                error: function(resultData) { alert(resultData) }
            });         
        });        
    </script>
    <script>
        $(document).on('click', ".loginButton", function() {             
            var uri='/login';
            var myValues = {
                username : document.getElementById("username").value,
                password : document.getElementById("password").value
            }
            $.ajax({
                method: 'POST',
                url: uri,
                data: myValues,
                dataType: "json",
                success: function(resultData) { 
                    alert("Login succesful");
                    window.location.replace("#products"); 
                    },
                error: function(resultData) { alert(resultData) }
            }); 
                   
        });
    </script>
    </head>
    <body>
        @section('content')
            <div class="page index">
                <table class="list"></table>
                <a href="#login" class="button"><?= trans('messages.Login') ?></a>
                <a href="#cart" class="button"><?= trans('messages.Go to cart') ?></a>
            </div>
            <div class="page cart">
                <table class="list"></table>
                <div>
                    <input id="name" class="solid" type="text" name="Name" placeholder="<?= trans('messages.Name') ?>"><br>
                    <input id="contact" class="solid" type="text" name="Contact" placeholder="<?= trans('messages.Contact details') ?>"><br>    
                    <input id="comments" class="solid" type="text" name="Comments" placeholder="<?= trans('messages.Comments') ?>"><br>
                    <div>
                        <a href="#" class="button"><?= trans('messages.Go to index') ?></a>
                        <button type = "submit" class="checkoutButton"><?= trans('messages.Checkout') ?></button>
                    </div>
                </div>   
            </div>
            <div class="page login">
                <input id="username" type = "text" class = "form-control" name = "username" required autofocus></br>
                <input id="password" type = "password" class = "form-control" name = "password" required></br>
                <button type = "submit" class="loginButton"><?= trans('messages.Login') ?></button>
            </div>
            <div class="page products">
            </div>

        @endsection
    </body>
</htnl>