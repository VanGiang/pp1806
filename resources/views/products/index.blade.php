@extends('layouts.app')

@section('content')
<div class="container">
    <main role="main">
      <div class="album py-5 bg-light">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-9 float-right">Order by:</div>
                <div class="col-md-3">
                    <select class="form-control" id="order">
                        <option value="1" {{ $selected[1] ? 'selected' : '' }} selected>Created at</option>
                        <option value="2" {{ $selected[2] ? 'selected' : '' }}>Price increase</option>
                        <option value="3" {{ $selected[3] ? 'selected' : '' }}>Price decrease</option>
                    </select>
                </div>
            </div>
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-4">
                      <div class="card mb-4 shadow-sm">
                        <img src="{{ 'https://kenh14cdn.com/2017/75614297gy1fksgmqczs9j21jj4401ky-1509003306586.jpg' }}" alt="{{ $product->product_name }}" height="100%" width="100%" class="hoverZoomLink">
                        <div class="card-body">
                            <p class="card-text">
                                Product name: {{ $product->product_name }}
                            </p>
                            <p class="card-text">
                                Price: ${{ $product->price }}
                            </p>
                          <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                              <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-secondary" role="button">View</a>
                              <a href="products/{{ $product->id }}/edit" class="btn btn-sm btn-outline-secondary" role="button">Edit</a>
                            </div>
                            <small class="text-muted">9 mins</small>
                          </div>
                        </div>
                      </div>
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-center">
              {{ $products->links() }}
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-info" role="button">Create product</a>
        </div>
      </div>
    </main>
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

        $('#order').change(function() {
            var selectValue = $(this).val();

            var redirectUrl = 'http://localhost:8000/?orderBy=created_at&type=desc';

            if (selectValue == 2) {
                redirectUrl = 'http://localhost:8000/?orderBy=price&type=asc';
            } else if (selectValue == 3) {
                redirectUrl = 'http://localhost:8000/?orderBy=price&type=desc';
            }

            window.location.replace(redirectUrl);
        });
    });
</script>
@endsection
