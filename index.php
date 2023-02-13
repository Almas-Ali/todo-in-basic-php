<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TODO List - CRUD</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
        <style type="text/css">
            :root {
                --theme-bg: #5feb9e;
            }
            footer{
                background: var(--theme-bg);
                color: #000;
                padding: 5px;
                margin-top: 5px;
            }
            nav {
                background: var(--theme-bg);
            }
        </style>
    </head>

    <body>

        <nav class="navbar navbar-expand-lg mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php $_SERVER['PHP_SELF']; ?>">TODO List</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">

        <?php

            require 'db_config.php';
            $db = get_database();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                if ($_POST['working_on'] == 'delete'){
                    $id = $_POST['id'];
                    $db->delete($id);
                        
                    echo '
                    <div class="alert alert-success" role="alert">
                        <strong>
                            TODO deleted successfully.
                        </strong>
                    </div>
                    ';
                }

                if ($_POST['working_on'] == 'update') {
                    $title = $_POST['title'];
                    $dsc   = $_POST['dsc'];
                    $id    = $_POST['id'];
                    $db->update($title, $dsc, $id);
                    
                    echo '
                <div class="alert alert-success" role="alert">
                    <strong>
                        TODO updated successfully.
                    </strong>
                </div>
                ';
                }

                if ($_POST['working_on'] == 'create') {

                    $title = $_POST['title'];
                    $dsc   = $_POST['dsc'];
                    $db->create($title, $dsc);
                    
                    echo '
                <div class="alert alert-success" role="alert">
                    <strong>
                        TODO added successfully.
                    </strong>
                </div>
                ';
                }
           }

            ?>

            <section class="row">
            <aside class="col-md-4">
                <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <h3 align="center">Add TODO Items</h3>
                    <input type="hidden" value="create" name="working_on">
                    <section class="form-group m-2">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="TODO title here" required>
                    </section>

                    <section class="form-group m-2">
                        <label for="dsc">Description</label>
                        <textarea name="dsc" cols="10" rows="8" class="form-control" placeholder="Description here"
                          required></textarea>
                    </section>

                    <section class="form-group m-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </section>

                </form>
            </aside>
            <aside class="col-md-8">
                <h3 align="center">Created TODO Lists</h3>
                <table class="table table-striped table-hover" id="todo_list">
                <thead>
                    <tr>
                    <th scope="col">Serial</th>
                    <th scope="col">Titles</th>
                    <th scope="col">Descriptions</th>
                    <!-- <th scope="col">Created At</th>
                    <th scope="col">Last Modified</th> -->
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // var_dump($db->create('This is title', 'This is dsc.......'));
                    // var_dump($db->get_all());
                    $rows = $db->get_all();
                    $i = 0;
                    foreach($rows as $row) {
                        $i += 1;
                    echo '
                    <tr>
                    <th scope="row">'. $i .'</th>
                    <td>'. $row["title"] .'</td>
                    <td>'. $row["dsc"] .'</td>
                    <td>
                        <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#data_edit_'.$i.'">Edit</a>
                            <input type="hidden" value="'.$row['id'].'" name="id">
                            <input type="hidden" value="delete" name="working_on">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                    </tr>

                    <div class="modal fade" id="data_edit_'.$i.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit TODO Item</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            
                            <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                                <input type="hidden" value="update" name="working_on">
                                <input type="hidden" value="'.$row['id'].'" name="id">
                                <section class="form-group m-2">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="TODO title here" required value="'. $row["title"].'">
                                </section>

                                <section class="form-group m-2">
                                    <label for="dsc">Description</label>
                                    <textarea name="dsc" cols="10" rows="8" class="form-control" placeholder="Description here"
                                      required>'. $row["dsc"].'</textarea>
                                </section>

                                <section class="form-group m-2">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </section>
                            </form>

                          </div>
                        </div>
                      </div>
                    </div>
                    ';
                    }
                ?>
                </tbody>

                </table>
            </aside>
            </section>

        </div>

        <footer>
            <p align="center">
                © Copyright 2023 TODO Applications. All rights reserved. 
            </p>
        </footer>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
          crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready( function () {
                $('#todo_list').DataTable();
            } );
        </script>
    </body>

</html>