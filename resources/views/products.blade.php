<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>laravel 6 Simple Table Ajax CRUD Application</title>
    <link rel="icon" type="image/png" href="{{asset('M.png')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <style>
        .container {
            padding: 0.5%;
        }
    </style>
  </head>
  <body>

    <div class="container">
      <h2 style="margin-top: 12px;" class="alert alert-success text-center">laravel 6 Simple Table Ajax CRUD Application</h2><br>
      <div class="row">
        <div class="col-12">
          <a
            href="javascript:void(0)"
            class="btn btn-success mb-2"
            id="add-product"
          >
            Add Product
          </a>
          {{--<a href="https://www.w3path.com/jquery-submit-form-ajax-php-laravel-5-7-without-page-load/"
             class="btn btn-secondary mb-2 float-right">Back to Post</a>--}}
          <table class="table table-bordered" id="laravel_crud">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Price</th>
                <td colspan="2">Action</td>
              </tr>
            </thead>
            <tbody id="products-crud">
              @if (!count($products))
                  <tr>
                    <td colspan=4 class="text-danger"><span style="margin-left: 50%">No Data Found</span></td>
                  </tr>
              @endif
              @foreach($products as $product)
                <tr id="product_id_{{ $product->id }}">
                  <td>{{ $product->id  }}</td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->price }}</td>
                  <td colspan="2">
                    <a
                      href="javascript:void(0)"
                      id="edit-product"
                      data-id="{{ $product->id }}"
                      class="btn btn-info mr-2"
                    >
                      Edit
                    </a>
                    <a
                      href="javascript:void(0)"
                      id="delete-product"
                      data-id="{{ $product->id }}"
                      class="btn btn-danger delete-user"
                    >
                      Delete
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ajax-crud-product-modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="productCrudModal"></h4>
          </div>
          <form id="productForm" name="productForm" class="form-horizontal">
            <div class="modal-body">
              <input type="hidden" name="product_id" id="product_id">

              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name"
                         value="" maxlength="50" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Price</label>
                <div class="col-sm-12">
                  <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value=""
                         required="">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="btn-save" value="create">
                Save changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            /*When user click on Add Product Button*/
            $("#add-product").click(function () {
                $('#btn-save').val('create-product')
                $('#productForm').trigger('reset')
                $('#productCrudModal').html('Add New Product')
                $('#ajax-crud-product-modal').modal('show')
            })

            /*When user click Edit Product Button*/
            $('body').on('click', '#edit-product', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: `products/${id}/edit`,
                    success: function (data) {
                        $('#productCrudModal').html('Edit Product')
                        $('#btn-save').val('edit-product')
                        $('#ajax-crud-product-modal').modal('show')
                        $('#product_id').val(data.id)
                        $('#name').val(data.name)
                        $('#price').val(data.price)
                    },
                    error: function () {
                        alert('Error!!')
                    }
                })
            })

            $('body').on('click', '#delete-product', function () {
                const id = $(this).data('id')

                if (confirm('Are you sure want to delete?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: `products/${id}`,
                        success: function (data) {
                            if (!data.error) {
                                $(`#product_id_${id}`).remove()
                            } else {
                                alert('Error')
                            }
                        },
                        error: function () {
                            alert('Error!')
                        }
                    })
                }
            })

            if ($('#productForm').length > 0) {
                $('#productForm').submit(function (e) {
                    e.preventDefault();
                    const actionType = $('#btn-save').val()
                    $('#btn-save').html('sending...')

                    const id = $('#product_id').val()
                    const name = $('#name').val()
                    const price = $('#price').val()

                    // alert(JSON.stringify({id, name, price}))

                    const data = $('#productForm').serialize()
                    // alert(data)

                    $.ajax({
                        data,
                        url: `products`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {
                            let product = `
                              <tr id="product_id_${data.id}">
                                <td>${data.id}</td>
                                <td>${data.name}</td>
                                <td>${data.price}</td>
                                <td colspan="2">
                                  <a
                                    href="javascript:void(0)"
                                    id="edit-product"
                                    data-id="${data.id}"
                                    class="btn btn-info mr-2"
                                  >
                                    Edit
                                  </a>
                                  <a
                                    href="javascript:void(0)"
                                    id="delete-product"
                                    data-id="${data.id}"
                                    class="btn btn-danger delete-user"
                                  >
                                    Delete
                                  </a>
                                </td>
                              </tr>
                            `
                            if (actionType === 'create-product') {
                                $('#products-crud').append(product)
                            } else if (actionType === 'edit-product') {
                                $(`#product_id_${id}`).replaceWith(product)
                            }

                            $('#productForm').trigger('reset')
                            $('#ajax-crud-product-modal').modal('hide')
                            $('#btn-save').html('Save Changes')
                        },
                        error: function (data) {
                            $('#ajax-crud-product-modal').modal('hide')
                            console.log('Error', data)
                            alert('ERROR!!')
                        }
                    })
                })
            }
        })
    </script>
  </body>
</html>
