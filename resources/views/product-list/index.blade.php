@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>

    <div class="card">
        <form action="{{route('product-list.index')}}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach($variants as $variant )
                            <option value="">Select One</option>
                            <option value="" disabled>{{$variant->title}}</option>
                            @foreach($variant->variant_details as $details)
                                <option value="{{$details->variant}}" style="padding-left:5px ">{{$details->variant}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table" id="productList">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{(($products->currentPage()-1)*10)+$loop->index + 1}}</td>
                            <td>{{$product->title}} <br> Created at : {{\Carbon\Carbon::parse($product->created_at)->diffForHumans()}}</td>
                            <td>{{$product->description}}</td>
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                    <dt class="col-sm-3 pb-0">
                                    @foreach($product->combos as $combo)
                                            {{$combo->variant_name}} <br>
                                        @endforeach
                                    </dt>

                                    <dd class="col-sm-9">
                                        <dl class="row mb-0">
                                            @foreach($product->combos as $combo)
                                            <dt class="col-sm-4 pb-0">Price : {{ number_format($combo->price,2) }}</dt>
                                            <dd class="col-sm-8 pb-0">InStock : {{ number_format($combo->stock,2) }}</dd>
                                            @endforeach
                                        </dl>
                                    </dd>
                                </dl>
                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product-list.edit', $product->id) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{(($products->currentPage()-1)*10)+1}} to {{(($products->currentPage()-1)*10)+count($products)}} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-2">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#"><</a></li>
                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                <li class="page-item {{$i == $products->currentPage()?'active':''}}"><a class="page-link" href="{{$products->path().'?page'.'='.$i}}">{{$i}}</a></li>
                            @endfor
                            <li class="page-item"><a class="page-link" href="#">></a></li>

                        </ul>
                    </nav>
{{--                    {!! $products->links() !!}--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
