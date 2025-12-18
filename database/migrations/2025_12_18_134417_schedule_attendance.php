<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Jadwal Kegiatan
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location_name')->nullable();
            $table->string('location_coordinates')->nullable(); // lat,long
            $table->boolean('is_online')->default(false);
            $table->string('meeting_link')->nullable();

            // Integrasi Google
            $table->string('google_event_id')->nullable();

            // Relations
            $table->foreignId('created_by')->constrained('users'); // Siapa pembuat jadwal
            $table->foreignId('division_id')->nullable()->constrained('divisions'); // Jadwal khusus divisi?

            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Sesi Absensi (QR Code Generator)
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->string('token')->unique(); // Token untuk QR
            $table->dateTime('valid_until');

            // Geo-fencing security
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius_meters')->default(100); // Batas jarak

            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 3. Data Kehadiran User (Adaptasi insider_attendances)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->nullable()->constrained('attendance_sessions');
            $table->foreignId('user_id')->constrained('users');

            $table->dateTime('clock_in');
            $table->dateTime('clock_out')->nullable();

            $table->enum('status', ['present', 'late', 'permission', 'sick', 'alpha'])->default('present');
            $table->text('notes')->nullable(); // Alasan ijin/sakit

            // Bukti Kehadiran
            $table->string('photo_evidence')->nullable(); // Foto selfie/lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('device_info')->nullable(); // Mencegah titip absen device

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Cuti / Izin (Adaptasi insider_leaves)
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type'); // sick, annual, etc
            $table->text('reason');
            $table->string('attachment')->nullable(); // Surat dokter
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_sessions');
        Schema::dropIfExists('schedules');
    }
};
