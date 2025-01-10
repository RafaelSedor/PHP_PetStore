<?php

namespace App\Controllers;

use App\Models\Category;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $this->render('categories/index', ['categories' => $categories]);
    }

    public function create()
    {
        $this->render('categories/create');
    }

    public function store(Request $request)
    {
        $data = $request->getAllParams();

        if (empty($data['name'])) {
            FlashMessage::danger('O nome da categoria é obrigatório.');
            $this->redirectBack();
        }

        if (Category::create($data['name'])) {
            FlashMessage::success('Categoria criada com sucesso.');
            $this->redirectTo('/admin/categories');
        } else {
            FlashMessage::danger('Erro ao criar a categoria.');
            $this->redirectBack();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $category = Category::findById($id);

        if (!$category) {
            FlashMessage::danger('Categoria não encontrada.');
            $this->redirectTo('/admin/categories');
        }

        $this->render('categories/edit', ['category' => $category]);
    }

    public function update(Request $request)
    {
        $data = $request->getAllParams();
        $category = Category::findById($data['id']);

        if (!$category) {
            FlashMessage::danger('Categoria não encontrada.');
            $this->redirectTo('/admin/categories');
        }

        if ($category->update($data['name'])) {
            FlashMessage::success('Categoria atualizada com sucesso.');
            $this->redirectTo('/admin/categories');
        } else {
            FlashMessage::danger('Erro ao atualizar a categoria.');
            $this->redirectBack();
        }
    }

    public function delete(Request $request)
    {
        $id = $request->getParam('id');

        if (Category::delete($id)) {
            FlashMessage::success('Categoria excluída com sucesso.');
        } else {
            FlashMessage::danger('Erro ao excluir a categoria.');
        }

        $this->redirectTo('/admin/categories');
    }
}
