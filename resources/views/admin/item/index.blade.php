@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Add Category</h3>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="cate_name" placeholder="Category Name">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Add Sub Category</h3>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="cat_id" placeholder="Category Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Sub Category Name</label>
                            <input type="text" class="form-control" name="cat_id" placeholder="Sub Category Name">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Item information</h3>
                </div>
                <div class="card-body">
                    <div class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="item_name" class="form-label">Item Name</label>
                                            <input type="text" class="form-control" name="item_name" placeholder="Item name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Item Category</label>
                                            <select class="form-control" data-live-search="true">
                                                <option data-tokens="ketchup mustard">Electronics</option>
                                                <option data-tokens="mustard">Burger, Shake and a Smile</option>
                                                <option data-tokens="frosting">Sugar, Spice and all things nice</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Item Sub Category</label>
                                            <select class="form-control" data-live-search="true">
                                                <option data-tokens="ketchup mustard">Router</option>
                                                <option data-tokens="mustard">mobile</option>
                                                <option data-tokens="frosting">Hardware</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Purchase Price</label>
                                            <input type="text" class="form-control" name="p_price" placeholder="Purchase Price">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Retail Price</label>
                                            <input type="text" class="form-control" name="r_price" placeholder="Retail Price">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Quantity</label>
                                            <input type="text" class="form-control" name="quantity" placeholder="Quantity">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="name" class="form-label">Status</label>
                                            <select class="form-control" data-live-search="true">
                                                <option data-tokens="ketchup mustard">Active</option>
                                                <option data-tokens="mustard">Dactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="stock_location" class="form-label">Stock Location</label>
                                            <input type="text" class="form-control" name="stock_location" placeholder="Stock Location">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="description" class="form-label">Item Description</label>
                                            <textarea type="text" class="form-control" name="description" placeholder="Description"></textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="img" class="form-label">Item Image</label>
                                            <input type="file" class="form-control" name="item_image" id="img">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection