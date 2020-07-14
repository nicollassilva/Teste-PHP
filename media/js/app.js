Site = {

    Initialize: function() {
        this.getVehicles();
        this.addVehicle();
        this.searchVehicles();
    },

    getVehicles: function() {
        let data = [];
        $.ajax({
            url: 'api/veiculos',
            dataType: 'JSON',
            type: 'GET',
            success: function(response) {
                html = '';
                if(response !== undefined) {
                response.length === undefined ? data[0] = response : data = response;
                data.forEach(function(e, i) {
                        html += `
                    <div class="row m-0 w-100 vehicle-info">
                        <div class="col-10">
                            <div class="col h-100 py-3 info">
                                <strong class="w-100 row text-truncate">${data[i].marca}</strong>
                                <strong class="w-100 row text-truncate">${data[i].veiculo}</strong>
                                <p class="w-100 row text-truncate">${data[i].ano}</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="col h-75 edit-button" id="${data[i].id}" data-toggle="tooltip" data-placement="left" title="Editar veículo"><i class="fas fa-edit"></i></div>
                        </div>
                    </div>`;
                });
                    $('.box-vehicles').append(html);
                    Site.editButton();
                    Site.showVehicleDetails();
                    $('[data-toggle="tooltip"]').tooltip()
                }
                
            },
        });

    },

    editButton: function() {
        $('.edit-button').on('click', function() {
            Site.searchVehicleToEdit($(this).attr('id'))
        })
    },

    searchVehicleToEdit: function(id) {
        $.ajax({
            url: 'api/veiculos/'+id,
            dataType: 'JSON',
            type: 'GET',
            success: function(response) {
                if(response !== undefined) {

                    html = `
                <div class="modal fade" id="edit" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editando o Veículo: ${response.veiculo}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <div class="modal-body">
                            <form action="api/veiculos" method="post" class="form edit">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="vehicle">Veículo</label>
                                        <input id="vehicle" class="form-control" type="text" name="vehicle" value="${response.veiculo}">
                                    </div>
                                </div>
                                <div class="col-12 d-flex">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="brand">Marca</label>
                                            <input id="brand" class="form-control" type="text" name="brand" value="${response.marca}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="year">Ano</label>
                                            <input id="year" class="form-control" type="number" name="year" min="1900" max="2020" value="${response.ano}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <textarea id="description" class="form-control" name="description">${response.descricao}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="sold" name="sold" ${response.vendido == 'true' ? 'checked' : ''}>
                                        <label class="custom-control-label" for="sold">Veículo vendido</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mx-auto d-block col-6 mt-2">Editar</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                        </div>
                    </div>
                </div>
                    `;
                    $('.modal#edit').length > 0 ? $('.modal#edit').remove() : ''
                    $('.modais').append(html)
                    $('#edit').modal('show')
                    Site.editVehicle(id)

                }
                
            },
        });
    },

    editVehicle: function(id) {

        $('form.edit').on('submit', function(e) {
            e.preventDefault();
            
            let form = $(this),
                button = $(this).find('button[type="submit"')
                info = $(this).serialize();

            $.ajax({
                url: 'api/veiculos/'+id,
                dataType: 'JSON',
                type: 'PUT',
                data: info,
                beforeSend: function() {
                    button.attr('disabled', true)
                },
                success: function(response) {
                    setTimeout(() => {
                        button.attr('disabled', false)
                        $('#edit').modal('hide')
                        $('.box-vehicles, .details-vehicle .details').html('')
                        Site.getVehicles()
                    }, 1000);
                    
                }
            });
        })

    },

    showVehicleDetails: function() {
        $('.vehicle-info').on('click', function() {
            $('.vehicle-info').hasClass('active') ? $('.vehicle-info').removeClass('active') : ''
            $(this).addClass('active')
            $.ajax({
                url: 'api/veiculos/' + $(this).find('.edit-button').attr('id'),
                dataType: 'JSON',
                type: 'GET',
                success: function(response) {
                    if(response !== undefined) {
                        html = `
                        <div class="row m-0 px-4 mt-1 py-2 details">
                            <strong class="w-100 row text-truncate">${response.veiculo}</strong>
                            <div class="col-12 d-flex">
                                <div class="col-6">
                                    <p class="text-muted m-0">MARCA</p>
                                    <h6 class="h6 w-100 text-truncate font-weight-bold">${response.marca}</h6>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted m-0">ANO</p>
                                    <h6 class="h6 w-100 text-truncate font-weight-bold">${response.ano}</h6>
                                </div>
                            </div>
                            <div class="row w-100 v-description border-bottom m-0 py-2">
                                ${response.descricao}
                            </div>
                            <button class="btn btn-danger mt-2 mx-auto d-block deleteVehicle" type="button" id="${response.id}">Excluir</button>
                        </div>
                        `;
                        $('.details-vehicle .details').length > 0 ? $('.details-vehicle .details').remove() : ''
                        $('.details-vehicle').append(html)
                        Site.deleteVehicle();
                    }
                }
            });
        })
    },

    deleteVehicle: function() {

        $('button.deleteVehicle').on('click', function() {
            let id = $(this).attr('id')

            $.ajax({
                url: 'api/veiculos/' + id,
                dataType: 'JSON',
                type: 'DELETE',
                success: function(response) {
                    $('.box-vehicles, .details-vehicle .details').html('')
                    Site.getVehicles();
                }
            })
        })

    },

    addVehicle: function() {

        $('form.add').on('submit', function(e) {
            e.preventDefault();

            let form = $(this),
                button = $(this).find('button[type="submit"')
                info = $(this).serialize();

            $.ajax({
                url: 'api/veiculos/',
                dataType: 'JSON',
                type: 'POST',
                data: info,
                beforeSend: function() {
                    button.attr('disabled', true)
                },
                success: function() {
                    setTimeout(() => {
                        button.attr('disabled', false)
                        form.each(function(){ this.reset(); })
                        $('.box-vehicles, .details-vehicle .details').html('')
                        $('#newVehicle').modal('hide');
                        Site.getVehicles();
                    }, 1000);
                }
            })
            
        })

    },

    searchVehicles: function() {

        $('form.edit-form').on('submit', function(e) {
            let data = [];
            e.preventDefault();
            let search = $(this).serialize(),
                button = $(this).find('button[type="submit"');

            $.ajax({
                url: 'api/veiculos/',
                dataType: 'JSON',
                type: 'POST',
                data: search,
                beforeSend: function() {
                    button.attr('disabled', true)
                },
                success: function(response) {
                    html = '';
                    button.attr('disabled', false)
                    setTimeout(() => {
                        if(response !== undefined) {
                            $('.box-vehicles, .details-vehicle .details').html('')
                            response.length === undefined ? data[0] = response : data = response;
                            data.forEach(function(e, i) {
                                    html += `
                                <div class="row m-0 w-100 vehicle-info">
                                    <div class="col-10">
                                        <div class="col h-100 py-3 info">
                                            <strong class="w-100 row text-truncate">${data[i].marca}</strong>
                                            <strong class="w-100 row text-truncate">${data[i].veiculo}</strong>
                                            <p class="w-100 row text-truncate">${data[i].ano}</p>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="col h-75 edit-button" id="${data[i].id}" data-toggle="tooltip" data-placement="left" title="Editar veículo"><i class="fas fa-edit"></i></div>
                                    </div>
                                </div>`;
                            });
                            $('.box-vehicles').append(html);
                            Site.editButton();
                            Site.showVehicleDetails();
                            $('[data-toggle="tooltip"]').tooltip()
                        }
                    }, 1000);
                }
            })
        })
    },
}

setTimeout(() => {
    Site.Initialize();
}, 10);