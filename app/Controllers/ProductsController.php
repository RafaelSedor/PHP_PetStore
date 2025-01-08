<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $this->render('products/index', ['products' => $products]);
    }

    public function create()
    {
        $categories = Category::all();
        $this->render('products/create', ['categories' => $categories]);
    }


    public function store(Request $request)
    {
        $data = $request->getAllParams();
        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->image_url = $data['image_url'];

        if ($product->save()) {
            FlashMessage::success('Produto criado com sucesso.');
            $this->redirectTo('/admin/products');
        } else {
            FlashMessage::danger('Erro ao criar o produto.');
            $this->redirectBack();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $product = Product::findById($id);

        if (!$product) {
            FlashMessage::danger('Produto não encontrado.');
            $this->redirectTo('/admin/products');
        }

        $categories = Category::all();
        $this->render('products/edit', ['product' => $product, 'categories' => $categories]);
    }


    public function update(Request $request)
    {
        $data = $request->getAllParams();
        $id = $data['id'];
        $product = Product::findById($id);

        if (!$product) {
            FlashMessage::danger('Produto não encontrado.');
            $this->redirectTo('/admin/products');
        }

        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->image_url = $data['image_url'];

        if ($product->save()) {
            FlashMessage::success('Produto atualizado com sucesso.');
            $this->redirectTo('/admin/products');
        } else {
            FlashMessage::danger('Erro ao atualizar o produto.');
            $this->redirectBack();
        }
    }

    public function delete(Request $request)
    {
        $id = $request->getParam('id');
        $product = Product::findById($id);

        if (!$product) {
            FlashMessage::danger('Produto não encontrado.');
            $this->redirectTo('/admin/products');
        }

        if ($product->delete()) {
            FlashMessage::success('Produto excluído com sucesso.');
        } else {
            FlashMessage::danger('Erro ao excluir o produto.');
        }

        $this->redirectTo('/admin/products');
    }
}
