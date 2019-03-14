@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if (session('status'))
                  <div class="alert alert-success">
                      {{ session('status') }}
                  </div>
                @endif
                <div class="card-header">
                    Product List
                    <input type="text" name="search" placeholder="Search product name" id="search" class="float-right">
                </div>

                <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Product name</th>
                          <th scope="col">Price</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody id="product-list">
                        @foreach ($products as $product)
                            <tr class="row_{{ $product->id }}">
                              <th scope="row">{{ $product->id }}</th>
                                <td>
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->product_name }}</a>
                                </td>
                              <td>{{ $product->price }}</td>
                              <td>
                                  <a href="products/{{ $product->id }}/edit" class="btn btn-info" role="button">Edit</a>
                                  <a href="#" class="btn btn-info btn-del-product" role="button" data-product-id="{{ $product->id }}">Delete</a>
                              </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="row justify-content-center">
                      {{ $products->links() }}
                    </div>
                    <a href="{{ route('products.create') }}" class="btn btn-info" role="button">Create product</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.btn-del-product').click(function() {
            if (confirm('You are sure?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var productId = $(this).data('product-id');
                var url = '/products/' + productId;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(result) {
                        if (result.status) {
                            $('.row_' + productId).remove();
                        } else {
                            alert(result.msg);
                        }
                    },
                    error: function() {
                        location.reload();
                    }
                });
            }
        });

        $('#search').keyup(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var keyWord = $(this).val();
            var url = '/products/search/' + keyWord;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(result) {
                    displayProduct(result);
                },
                error: function() {
                    location.reload();
                }
            });
        });

        function displayProduct(products) {
            $('#product-list').html('');

            $.each(products, function(index, product) {
                var row = '<tr class="row_' + product.id + '">'
                        +  '<th scope="row">' + product.id + '</th>'
                        +  '<td>'
                                + '<a href="#">' + product.product_name + '</a>'
                            + '</td>'
                        +  '<td>' + product.price + '</td>'
                        +  '<td>'
                            + '<a href="#" class="btn btn-info" role="button">Edit</a>'
                            + '<a href="#" class="btn btn-info btn-del-product" role="button" data-product-id="' + product.id + '>Delete</a>'
                        +  '</td>'
                    + '</tr>';

                $('#product-list').append(row);
            });
        }
    });
</script>
@endsection
