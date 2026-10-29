<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Support\CompanyProfile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/** Kontak & alamat perusahaan: telepon/WhatsApp, email, alamat, dan peta. */
class CompanyController extends Controller
{
    public function edit(): View
    {
        return view('admin.company', ['company' => CompanyProfile::current()]);
    }

    public function update(CompanyRequest $request): RedirectResponse
    {
        CompanyProfile::save($request->validated());

        return redirect()->route('admin.company.edit')->with('status', 'Kontak dan alamat berhasil diperbarui.');
    }
}