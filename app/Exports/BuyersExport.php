<?php

namespace App\Exports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BuyersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Buyer::select(
            'company_name',
            'code',
            'email',
            'phone',
            'country',
            'address',
            'status',
            'note'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Code',
            'Email',
            'Phone',
            'Country',
            'Address',
            'Status',
            'Note',
        ];
    }

    public function map($buyer): array
    {
        return [
            $buyer->company_name,
            $buyer->code,
            $buyer->email,
            $buyer->phone,
            $buyer->country,
            $buyer->address,
            $buyer->status ? 'Active' : 'Inactive',
            $buyer->note,
        ];
    }
}
