<?php

namespace App\Imports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class BuyersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * Map each row to the Buyer model
     */
    public function model(array $row)
    {
        // Trim data to avoid hidden spaces causing duplicates
        $companyName = trim($row['company_name']);
        $code = trim($row['code']);

        // Check if company_name or code already exists
        $exists = Buyer::where('company_name', $companyName)
            ->orWhere('code', $code)
            ->exists();

        if ($exists) {
            // Duplicate found, skip this row
            return null;
        }

        // Insert new buyer
        return new Buyer([
            'company_name' => $companyName,
            'code' => $code,
            'email' => isset($row['email']) ? trim($row['email']) : null,
            'phone' => isset($row['phone']) ? trim($row['phone']) : null,
            'country' => isset($row['country']) ? trim($row['country']) : null,
            'address' => isset($row['address']) ? trim($row['address']) : null,
            'status' => isset($row['status'])
                ? (strtolower(trim($row['status'])) === 'active' ? 1 : 0)
                : 0,
            'note' => isset($row['note']) ? trim($row['note']) : null,
            'user_id' => auth()->id() ?? 1, // adjust as needed
        ]);
    }

    /**
     * Validation rules
     * Here we only validate company_name and code are present
     */
    public function rules(): array
    {
        return [
            '*.company_name' => 'required|string|max:255',
            '*.code' => 'required|string|max:255',
        ];
    }

    /**
     * Optional: Customize validation messages
     */
    public function customValidationMessages()
    {
        return [
            '*.company_name.required' => 'Company name is required',
            '*.code.required' => 'Code is required',
        ];
    }
}
