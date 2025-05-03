@extends('layouts.master')

@section('title', 'Products List')

@section('content')
<div class="container">
    <!-- <h1>Products List</h1> -->

    <!-- Search Form -->
    <form>
        <div class="row">
            <div class="col col-10">
                    <h1>Products</h1>
            </div>
            <div class="col col-2">
                @can('edit_products')
                @auth
                <a href= "{{route('products_edit')}}" class= "btn btn-success form-control"> Add Product </a>
                @endauth    
                @endcan 
            </div>

            <div class="col col-sm-2">
                <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{request()->keywords}}" />
            </div>
            <div class="col col-sm-2">
                <input name="min_price" type="number" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}" />
            </div>
            <div class="col col-sm-2">
                <input name="max_price" type="number" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}" />
            </div>
            <div class="col col-sm-2">
                <select name="order_by" class="form-select">
                    <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                    <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                    <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
                </select>
            </div>
            <div class="col col-sm-2">
                <select name="order_direction" class="form-select">
                    <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                    <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                    <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
                </select>
            </div>
            <div class="col col-sm-1">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col col-sm-1">
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </form>

    @if(!empty(request()->keywords))
        <div class='card mt-2'>
            <div class='card-body'>
                view search results: <span>{{!! request()->keywords !!}}</span>
            </div>
        </div>
    @endif

    <!-- Product Listing -->
    @foreach($products as $product)
        <div class="card mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col col-sm-12 col-lg-4">
                        <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{ $product->name }}" width="100%">
                    </div>
                    
                    <div class="col col-sm-12 col-lg-8 mt-3">

                        <div class="row mb-2">

                            <div class="col-8">
                                <h3>{{$product->name}}</h3>
                            </div>

                            <div class="col col-2">
                                @can('edit_products')
                                @auth
                                    <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                                @endauth
                                @endcan
                            </div>

                            <div class="col col-2">
                                @can('delete_products')
                                @auth
                                    <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                                @endauth
                                @endcan
                            </div>
                        </div>  
                          

                        <table class="table table-striped">
                            <tr>
                                <th width="20%">Name</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $product->model }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $product->description }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{$product->price}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection