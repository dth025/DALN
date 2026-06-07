<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification as AppNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $upcomingAppointments = Appointment::where('user_id', auth()->id())
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('appointments', compact('doctors', 'upcomingAppointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        $appointmentDate = Carbon::parse($request->appointment_date);

        // Check for conflict: exact same datetime for the doctor
        $conflict = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $appointmentDate)
            ->exists();

        if ($conflict) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Bác sĩ đã có lịch tại thời gian này. Vui lòng chọn khung giờ khác.'], 409);
            }

            return back()->withErrors(['appointment_date' => 'Bác sĩ đã có lịch tại thời gian này. Vui lòng chọn khung giờ khác.'])->withInput();
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'specialty' => $doctor->specialty,
            'appointment_date' => $appointmentDate,
            'status' => 'scheduled',
        ]);

        // Create a notification for the patient (confirmation)
        try {
            AppNotification::create([
                'user_id' => Auth::id(),
                'type' => 'appointment',
                'title' => 'Xác nhận đặt lịch khám',
                'message' => "Bạn đã đặt lịch với {$doctor->name} vào " . $appointmentDate->format('d/m/Y H:i'),
                'link' => route('appointments'),
            ]);
        } catch (\Exception $e) {
            // ignore notification errors
        }

        // Send email to doctor (best-effort)
        try {
            $doctorEmail = $doctor->email;
            if ($doctorEmail) {
                $mailBody = "Bác sĩ {$doctor->name},\n\nBạn có cuộc hẹn mới từ người dùng ID=" . Auth::id() . " vào " . $appointmentDate->format('d/m/Y H:i') . ".\n\nTruy cập trang quản lý bác sĩ để xem chi tiết.";
                Mail::raw($mailBody, function ($m) use ($doctorEmail) {
                    $m->to($doctorEmail)->subject('Lịch khám mới');
                });
            }
        } catch (\Exception $e) {
            // ignore mail errors to avoid breaking booking flow
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đặt lịch thành công', 'appointment' => $appointment]);
        }

        return redirect()->route('appointments')->with('success', 'Đặt lịch khám thành công!');
    }

    public function reschedule(Request $request, $id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'scheduled')
            ->firstOrFail();

        $request->validate([
            'appointment_date' => 'required|date',
        ]);

        $newDate = Carbon::parse($request->appointment_date);

        $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
            ->where('appointment_date', $newDate)
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($conflict) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Bác sĩ đã có lịch tại thời gian này. Vui lòng chọn khung giờ khác.'], 409);
            }

            return back()->withErrors(['appointment_date' => 'Bác sĩ đã có lịch tại thời gian này. Vui lòng chọn khung giờ khác.'])->withInput();
        }

        $appointment->appointment_date = $newDate;
        $appointment->save();

        try {
            AppNotification::create([
                'user_id' => Auth::id(),
                'type' => 'appointment',
                'title' => 'Cập nhật lịch khám',
                'message' => "Lịch khám với {$appointment->doctor_name} đã được đổi sang " . $newDate->format('d/m/Y H:i'),
                'link' => route('appointments'),
            ]);
        } catch (\Exception $e) {
            // ignore notification errors
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đổi lịch thành công', 'appointment' => $appointment]);
        }

        return redirect()->route('appointments')->with('success', 'Đổi lịch khám thành công!');
    }
}
