<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Generate email if the email field is null or empty
        $email = $row['email'] ?? strtolower($row['name']) . '@gmail.com';

        return new Student([
            'name' => $row['name'],  // Ensure this column exists in your CSV
            'email' => $email,       // Use existing email or generated email
            'rfid' => $row['rfid'],  // Ensure this column exists in your CSV
            'school_id' => $row['school_id'], // Ensure this column exists in your CSV
        ]);
    }
}
