<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác nhận lịch hẹn khám bệnh</title>
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
            background-color: #0078d7;
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
            color: #0078d7;
        }

        .info-box {
            background-color: #f0f7ff;
            border-left: 5px solid #0078d7;
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
            color: #0078d7;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Bệnh viện Hồng Phúc</h1>
        </div>

        <div class="content">
            <h2>Xin chào {{ $appointment->patient->name ?? 'Quý bệnh nhân' }},</h2>
            <p>Bác sĩ <strong>{{ $appointment->doctor->user->name ?? '...' }}</strong> đã <strong>xác nhận</strong> lịch
                hẹn khám của bạn.</p>

            <div class="info-box">
                <p><strong>📅 Ngày khám:</strong>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</p>
                <p><strong>⏰ Thời gian:</strong> {{ $appointment->schedule->schedule->start_time ?? 'Chưa có' }} -
                    {{ $appointment->schedule->schedule->end_time ?? '' }}</p>
                <p><strong>👨‍⚕️ Bác sĩ phụ trách:</strong> {{ $appointment->doctor->user->name ?? 'Đang cập nhật' }}
                </p>
            </div>

            <p>Chúng tôi rất mong được đón tiếp bạn tại <strong>Bệnh viện Hồng Phúc</strong>.
                Vui lòng đến trước giờ hẹn 15 phút để hoàn tất thủ tục tiếp đón.</p>

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
