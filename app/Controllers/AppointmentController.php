<?php

namespace App\Controllers;

use App\Models\Appointment;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class AppointmentController extends Controller
{
    public function request(Request $request)
    {
        $data = $request->getAllParams();
        if (!isset($data['user_id']) || !isset($data['pet_id']) || !isset($data['service_id'])) {
            FlashMessage::danger('Dados incompletos para solicitar o serviço.');
            $this->redirectBack();
        }

        $appointment = new Appointment();
        $appointment->user_id = $data['user_id'];
        $appointment->pet_id = $data['pet_id'];
        $appointment->service_id = $data['service_id'];
        $appointment->status = 'solicitado';

        if ($appointment->save()) {
            FlashMessage::success('Serviço solicitado com sucesso!');
            $this->redirectTo('/user/services/track');
        } else {
            FlashMessage::danger('Erro ao solicitar o serviço.');
            $this->redirectBack();
        }
    }

    public function index()
    {
        $appointments = Appointment::getAllWithDetails();
        $this->render('appointments/index', ['appointments' => $appointments]);
    }

    public function updateStatus(Request $request)
    {
        $data = $request->getAllParams();

        if (empty($data['id']) || empty($data['status'])) {
            FlashMessage::danger('Dados inválidos para atualização.');
            $this->redirectBack();
        }

        $appointment = Appointment::findById((int) $data['id']);

        if (!$appointment) {
            FlashMessage::danger('Agendamento não encontrado.');
            $this->redirectBack();
        }

        $appointment->status = $data['status'];

        if ($appointment->save()) {
            FlashMessage::success('Status atualizado com sucesso.');
        } else {
            FlashMessage::danger('Erro ao atualizar o status.');
        }

        $this->redirectTo('/admin/appointments');
    }
}
