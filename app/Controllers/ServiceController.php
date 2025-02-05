<?php

namespace App\Controllers;

use App\Models\Service;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $this->render('services/index', ['services' => $services]);
    }

    public function create()
    {
        $this->render('services/create');
    }

    public function store(Request $request)
    {
        $data = $request->getAllParams();
        $file = $_FILES['image_file'] ?? null;

        $service = new Service();
        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->price = $data['price'];

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $service->image_url = '/assets/uploads/' . $fileName;
            } else {
                FlashMessage::danger('Erro ao fazer upload do arquivo.');
                $this->redirectBack();
            }
        } else {
            $service->image_url = $data['image_url'] ?? null;
        }

        if ($service->save()) {
            FlashMessage::success('Serviço criado com sucesso.');
            $this->redirectTo('/admin/services');
        } else {
            FlashMessage::danger('Erro ao criar o serviço.');
            $this->redirectBack();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $service = Service::findById($id);

        if (!$service) {
            FlashMessage::danger('Serviço não encontrado.');
            $this->redirectTo('/admin/services');
        }

        $this->render('services/edit', ['service' => $service]);
    }

    public function update(Request $request)
    {
        $data = $request->getAllParams();
        $file = $_FILES['image_file'] ?? null;
        $id = $data['id'];
        $service = Service::findById($id);

        if (!$service) {
            FlashMessage::danger('Serviço não encontrado.');
            $this->redirectTo('/admin/services');
        }

        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->price = $data['price'];

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $service->image_url = '/assets/uploads/' . $fileName;
            } else {
                FlashMessage::danger('Erro ao fazer upload do arquivo.');
                $this->redirectBack();
            }
        } elseif (isset($data['image_url']) && $data['image_url']) {
            $service->image_url = $data['image_url'];
        }

        if ($service->save()) {
            FlashMessage::success('Serviço atualizado com sucesso.');
            $this->redirectTo('/admin/services');
        } else {
            FlashMessage::danger('Erro ao atualizar o serviço.');
            $this->redirectBack();
        }
    }

    public function delete(Request $request)
    {
        $id = $request->getParam('id');
        $service = Service::findById($id);

        if (!$service) {
            FlashMessage::danger('Serviço não encontrado.');
            $this->redirectTo('/admin/services');
        }

        if ($service->delete()) {
            FlashMessage::success('Serviço excluído com sucesso.');
        } else {
            FlashMessage::danger('Erro ao excluir o serviço.');
        }

        $this->redirectTo('/admin/services');
    }

    public function track(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;

        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para acompanhar os serviços.');
            $this->redirectTo('/login');
        }

        $services = Service::findByUserId($user->id);

        if (empty($services)) {
            FlashMessage::info('Você ainda não possui serviços solicitados.');
        }

        $this->render('services/track', [
            'user' => $user,
            'services' => $services,
        ]);
    }
}
