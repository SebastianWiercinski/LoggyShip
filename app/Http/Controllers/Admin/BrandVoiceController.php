<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandVoice;
use App\Services\AI\BrandVoiceAnalyzer;
use Illuminate\Http\Request;

class BrandVoiceController extends Controller
{
    public function index()
    {
        $voices = BrandVoice::orderBy('name')->get();

        return view('admin.brand-voices.index', compact('voices'));
    }

    public function edit(BrandVoice $brandVoice)
    {
        return view('admin.brand-voices.edit', compact('brandVoice'));
    }

    public function update(Request $request, BrandVoice $brandVoice)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|string|max:10',
            'sample_text' => 'nullable|string|max:10000',
            'no_go_words' => 'nullable|string|max:1000',
        ]);

        $sampleTexts = $validated['sample_text']
            ? array_filter(array_map('trim', explode("---", $validated['sample_text'])))
            : [];

        $brandVoice->update([
            'name' => $validated['name'],
            'language' => $validated['language'],
            'sample_texts' => $sampleTexts,
            'no_go_words' => $validated['no_go_words'],
        ]);

        return redirect()->route('admin.brand-voices.index')
            ->with('success', 'Brand voice updated.');
    }

    public function analyze(BrandVoice $brandVoice, BrandVoiceAnalyzer $analyzer)
    {
        try {
            $analyzer->analyzeAndSave($brandVoice);

            return back()->with('success', 'Brand voice profile analyzed and saved.');
        } catch (\Exception $e) {
            return back()->with('error', 'Analysis failed: '.$e->getMessage());
        }
    }

    public function setActive(BrandVoice $brandVoice)
    {
        BrandVoice::where('is_active', true)->update(['is_active' => false]);
        $brandVoice->update(['is_active' => true]);

        return back()->with('success', "{$brandVoice->name} is now the active brand voice.");
    }
}
