<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông báo từ chối lịch hẹn khám bệnh</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background-color: #d32f2f;
            color: white;
            text-align: center;
            padding: 20px 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 25px;
            color: #333;
            line-height: 1.6;
        }

        .content h2 {
            color: #d32f2f;
        }

        .info-box {
            background-color: #fff4f4;
            border-left: 5px solid #d32f2f;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 15px;
        }

        .footer {
            background-color: #f8f9fb;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777;
        }

        .footer a {
            color: #d32f2f;
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background-color: #d32f2f;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #b71c1c;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Bệnh viện Hồng Phúc</h1>
        </div>


        @php
            $rejecterName = $appointment->canceled_by_receptionist_id
                ? 'Lễ tân'.$appointment->canceledByReceptionist->name ?? 'Lễ tân'
                : $appointment->doctor->user->name ?? 'Bác sĩ';
        @endphp
        <div class="content">
            <h2>Xin chào {{ $appointment->patient->name ?? 'Quý bệnh nhân' }},</h2>
            <p>Chúng tôi rất tiếc phải thông báo rằng lịch hẹn khám của bạn đã <strong style="color:#d32f2f;">bị từ
                    chối</strong>.</p>

            <p>Lịch hẹn khám của bạn đã <strong style="color:#d32f2f;">bị từ chối</strong> bởi {{ $rejecterName }}.</p>

            <div class="info-box">
                <p><strong>📅 Ngày khám:</strong>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</p>
                <p><strong>👨‍⚕️ Bác sĩ:</strong> {{ $appointment->doctor->user->name ?? 'Đang cập nhật' }}</p>
                <p><strong>💬 Ghi chú:</strong> {{ $appointment->notes ?? 'Không có ghi chú' }}</p>
                <p><strong>⏰ Thời gian:</strong> {{ $appointment->schedule->schedule->start_time ?? 'Chưa có' }} -
                    {{ $appointment->schedule->schedule->end_time ?? '' }}</p>
            </div>

            <p>Rất mong bạn thông cảm vì sự bất tiện này.
                Bạn có thể đặt lại lịch hẹn khác vào thời gian phù hợp hơn.</p>

            <p style="text-align:center;">
                <a href="{{ route('appointment') }}" class="btn">Đặt lại lịch hẹn</a>
            </p>

            <p>Trân trọng,<br>
                <strong>Đội ngũ Hồng Phúc</strong>
            </p>
        </div>

        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời thư này.</p>
            <p>© {{ date('Y') }} Bệnh viện Hồng Phúc | <a href="#">hongphuchospital.vn</a></p>
        </div>
    </div>
</body>

</html>
