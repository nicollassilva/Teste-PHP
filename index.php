<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste PHP - Full Stack</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="media/css/app.css?t=<?= time() ?>">
</head>

<body>
    <div class="container bg-white">
        <div class="row border w-100 ml-0">
            <div class="col-3 border-right text-center p-3 font-weight-bold text-primary">Página Inicial</div>
        </div>
        <div class="row w-100 ml-0 py-3">
            <form action="api/veiculos" method="post" class="form w-100 px-2 edit-form">
                <div class="col-auto">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control search-vehicle" placeholder="Buscar veículo por: nome, marca ou ano" name="search">
                        <div class="input-group-prepend">
                            <button type="submit" class="input-group-text btn-search"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="input-group-prepend">
                            <div class="input-group-text new-vehicle" data-toggle="modal" data-target="#newVehicle"><i class="fas fa-plus"></i></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container container-data mt-2">
        <div class="row ml-0 container-vehicle w-100 d-flex justify-content-around">
            <div class="col-lg-7 col-12 mt-2 px-1">
                <div class="col m-0 w-100 rounded bg-white shadow-sm">
                    <div class="row font-weight-bold py-2 px-3">Lista de Veículos</div>
                    <div class="row border m-0 overflow-auto box-vehicles"></div>
                    <div class="row m-0 py-2 d-flex justify-content-end">
                        <button class="btn btn-light" disabled><i class="fas fa-angle-double-left"></i></button>
                        <button class="btn btn-light" disabled><i class="fas fa-angle-left"></i></button>
                        <button class="btn btn-light" disabled><i class="fas fa-angle-right"></i></button>
                        <button class="btn btn-light" disabled><i class="fas fa-angle-double-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-12 mt-2 px-1">
                <div class="col rounded bg-white shadow-sm details-vehicle">
                    <div class="row font-weight-bold py-2 px-3 border-bottom w-95 mx-auto d-block">Detalhes do Veículo</div>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="media/js/jquery.min.js"></script>
    <script src="media/js/popper.min.js"></script>
    <script src="media/js/bootstrap.min.js"></script>
    <script src="media/js/fontawesome.min.js"></script>
    <script src="media/js/app.js?t=<?= time() ?>"></script>
</body>
</html>

<div class="modais">
    <div class="modal fade" id="newVehicle" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Novo Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <form action="api/veiculos" method="post" class="form add">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="vehicle">Veículo</label>
                            <input id="vehicle" class="form-control" type="text" name="vehicle" required>
                        </div>
                    </div>
                    <div class="col-12 d-flex">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="brand">Marca</label>
                                <input id="brand" class="form-control" type="text" name="brand" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="year">Ano</label>
                                <input id="year" class="form-control" type="number" name="year" min="1900" max="2020" value="2020" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea id="description" class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mx-auto d-block col-6">Adicionar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
            </div>
        </div>
    </div>
</div>