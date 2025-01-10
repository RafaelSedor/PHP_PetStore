<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\ShoppingList;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class CartController extends Controller
{
    private function getCartKey(int $userId): string
    {
        return "cart_user_{$userId}";
    }

    public function index(Request $request)
    {
        $userId = $request->getParam('id');
        $user = Auth::user();

        if ($user->id !== (int) $userId && $user->role !== 'admin') {
            FlashMessage::danger('Você não tem permissão para acessar este carrinho.');
            $this->redirectTo('/');
        }

        $cartKey = $this->getCartKey($userId);
        $cart = $_SESSION[$cartKey] ?? [];
        $products = [];

        foreach ($cart as $productId) {
            $product = Product::findById($productId);
            if ($product) {
                $products[] = $product;
            }
        }

        $this->render('cart/index', ['products' => $products, 'user' => $user]);
    }

    public function add(Request $request)
    {
        $userId = $request->getParam('id');
        $user = Auth::user();

        if ($user->id !== (int) $userId) {
            FlashMessage::danger('Você não tem permissão para adicionar itens a este carrinho.');
            $this->redirectTo('/');
        }

        $productId = $request->getParam('product_id');
        if (!$productId) {
            FlashMessage::danger('Produto inválido.');
            $this->redirectBack();
        }

        $cartKey = $this->getCartKey($userId);

        if (!isset($_SESSION[$cartKey])) {
            $_SESSION[$cartKey] = [];
        }

        $_SESSION[$cartKey][] = $productId;
        FlashMessage::success('Produto adicionado ao carrinho.');
        $this->redirectBack();
    }

    public function remove(Request $request)
    {
        $userId = $request->getParam('id');
        $user = Auth::user();

        if ($user->id !== (int) $userId) {
            FlashMessage::danger('Você não tem permissão para remover itens deste carrinho.');
            $this->redirectTo('/');
        }

        $productId = $request->getParam('product_id');
        $cartKey = $this->getCartKey($userId);

        if (!$productId || !isset($_SESSION[$cartKey])) {
            FlashMessage::danger('Produto inválido.');
            $this->redirectBack();
        }

        $_SESSION[$cartKey] = array_filter($_SESSION[$cartKey], function ($id) use ($productId) {
            return $id != $productId;
        });

        FlashMessage::success('Produto removido do carrinho.');
        $this->redirectBack();
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartKey = $this->getCartKey($user->id);
        $cart = $_SESSION[$cartKey] ?? [];

        if (empty($cart)) {
            FlashMessage::danger('Seu carrinho está vazio.');
            $this->redirectBack();
        }

        $shoppingListId = ShoppingList::create(['user_id' => $user->id]);

        if (!$shoppingListId) {
            FlashMessage::danger('Erro ao criar lista de compras.');
            $this->redirectBack();
        }

        foreach ($cart as $productId) {
            ShoppingList::addItem($shoppingListId, $productId, 1);
        }

        FlashMessage::success('Compra finalizada com sucesso.');
        unset($_SESSION[$cartKey]);
        $this->redirectTo("/");
    }
}
