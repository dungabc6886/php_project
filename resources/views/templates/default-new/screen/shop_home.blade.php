@php
/*
$layout_page = shop_home
*/ 
@endphp

@extends($templatePath.'.layout')
@php
$productsNew = $modelProduct->start()->getProductLatest()->setlimit(8)->getData();
$productsHot = $modelProduct->start()->getProductHot()->getData();
/*
Model product get product oder_detail merge product promotion
*/
$productsHotQty = count($productsHot);
if ( $productsHotQty < sc_config('product_hot') || $productsHotQty < 4 ){
    $configHot = sc_config('product_hot');
    if ($configHot < 4) {
        $configHot = 4;
    }
    $limit = $configHot - $productsHotQty;
    $limit = ($limit + $productsHotQty > 4) ? $limit : (4 - $productsHotQty);
    $orderDetail = $modelProduct->start()->getProductOrder($limit, 1, $productsHot->pluck('id'))->getData();
    $productsHot = $orderDetail->merge($productsHot);
}
//
$productsBuild = $modelProduct->start()->getProductBuild()->getData();
$productsGroup = $modelProduct->start()->getProductGroup()->getData();
@endphp

@section('center')
<div class="container-sm container product-list features">
    <div class="product-list-title">
        {{ trans('front.features_items') }}
    </div>
    <div class="container">
        <div class="row">
            @foreach ($productsNew as $key => $product_new)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                <div class="product-item">
                    <div class="product-main">
                        <div class="product-group">
                            @if ($product_new->price != $product_new->getFinalPrice() && $product_new->kind !=
                            SC_PRODUCT_GROUP)
                            <img src="{{ asset($templateFile.'/images/home/sale.png') }}" class="new" alt="" />
                            @elseif($product_new->kind == SC_PRODUCT_BUILD)
                            <img src="{{ asset($templateFile.'/images/home/bundle.png') }}" class="new" alt="" />
                            @elseif($product_new->kind == SC_PRODUCT_GROUP)
                            <img src="{{ asset($templateFile.'/images/home/group.png') }}" class="new" alt="" />
                            @endif
                        </div>
                        <div class="product-photo">
                            <a href="{{ $product_new->getUrl() }}">
                                <img src="{{ asset($product_new->getThumb()) }}" alt="{{ $product_new->name }}">
                            </a>
                        </div>
                        <div class="product-name">
                            <a href="{{ $product_new->getUrl() }}">
                                <h4>{{ $product_new->name }}</h4>
                            </a>
                        </div>
                        <div class="product-price">
                            {!! $product_new->showPrice() !!}
                        </div>
                        <div class="product-add-cart">
                            @if ($product_new->allowSale())
                            <a class="btn btn-default"
                                onClick="addToCartAjax('{{ $product_new->id }}','default')">
                                <i class="fa fa-shopping-cart"></i> <span>{{trans('front.add_to_cart')}}</span>
                            </a>
                            @else
                            &nbsp;
                            @endif
                        </div>

                    </div>
                    <div class="product-choose">
                        <ul class="nav nav-pills nav-justified">
                            <li>
                                <a onClick="addToCartAjax('{{ $product_new->id }}','wishlist')">
                                    <i class="fas fa-heart"></i> {{trans('front.add_to_wishlist')}}
                                </a>
                            </li>
                            <li>
                                <a onClick="addToCartAjax('{{ $product_new->id }}','compare')">
                                    <i class="fas fa-exchange-alt"></i> {{trans('front.add_to_compare')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    {{-- <div class="btn-view">
            <a href="" class="">Xem thêm</a>
        </div> --}}
</div>

<div class="container-sm container product-list1 bkg-blue new" @php if( empty($productsHot->toArray()) ) echo 'style="display: none;"'; @endphp>
    <div class="product-list-title">
        {{ trans('front.products_hot') }}
        <div class="arrow-slider-custom">
            <div class="next">
            </div>
            <div class="prev">
            </div>
        </div>
    </div>
    <div class="row product-list-items">
        <div class="col-12">
            <div class="slider">
                @for ( $i = count($productsHot) - 1 ; $i >= 0; $i-- )
                <div class="col-12">
                    <div class="product-item">
                        <div class="product-main">
        
                            <div class="product-photo">
                                <a href="{{ $productsHot[$i]->getUrl() }}">
                                    <img src="{{ asset($productsHot[$i]->getThumb()) }}" alt="{{ $productsHot[$i]->name }}">
                                </a>
                            </div>
                            <div class="product-name">
                                <a href="{{ $productsHot[$i]->getUrl() }}">
                                    <h4>{{ $productsHot[$i]->name }}</h4>
                                </a>
                            </div>
                            <div class="product-price">
                                {!! $productsHot[$i]->showPrice() !!}
                            </div>
                            <div class="product-add-cart">
                                @if ($productsHot[$i]->allowSale())
                                <a class="btn btn-default"
                                    onClick="addToCartAjax('{{ $productsHot[$i]->id }}','default')">
                                    <i class="fa fa-shopping-cart"></i> <span>{{trans('front.add_to_cart')}}</span>
                                </a>
                                @else
                                &nbsp;
                                @endif
                            </div>
        
                        </div>
                        <div class="product-choose">
                            <ul class="nav nav-pills nav-justified">
                                <li>
                                    <a onClick="addToCartAjax('{{ $productsHot[$i]->id }}','wishlist')">
                                        <i class="fas fa-heart"></i> {{trans('front.add_to_wishlist')}}
                                    </a>
                                </li>
                                <li>
                                    <a onClick="addToCartAjax('{{ $productsHot[$i]->id }}','compare')">
                                        <i class="fas fa-exchange-alt"></i> {{trans('front.add_to_compare')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endfor
    
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')

@endpush
