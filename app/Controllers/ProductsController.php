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
        $file = $_FILES['image_file'] ?? null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['image_url'] = '/assets/uploads/' . $fileName;
            } else {
                FlashMessage::danger('Erro ao fazer upload do arquivo.');
                $this->redirectBack();
            }
        }

        if (!isset($data['categories']) || !is_array($data['categories']) || empty($data['categories'])) {
            FlashMessage::danger('É necessário selecionar pelo menos uma categoria válida.');
            $this->redirectBack();
        }

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->categories  = $data['categories'];
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
        $file = $_FILES['image_file'] ?? null;
        $id = $data['id'];
        $product = Product::findById($id);

        if (!$product) {
            FlashMessage::danger('Produto não encontrado.');
            $this->redirectTo('/admin/products');
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['image_url'] = '/assets/uploads/' . $fileName;
            } else {
                FlashMessage::danger('Erro ao fazer upload do arquivo.');
                $this->redirectBack();
            }
        }

        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->categories  = $data['categories'];
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
