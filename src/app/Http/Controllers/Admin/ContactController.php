<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $contacts = Contact::with('category')
            ->keyword($request->input('keyword'), $request->input('match', 'partial'))
            ->gender($request->input('gender'))
            ->category($request->input('category_id'))
            ->createdDate($request->input('date'))
            ->orderByDesc('created_at')
            ->paginate(7)
            ->withQueryString();

        return view('admin.contacts.index', compact('contacts', 'categories'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Contact::with('category')
            ->keyword($request->input('keyword'), $request->input('match', 'partial'))
            ->gender($request->input('gender'))
            ->category($request->input('category_id'))
            ->createdDate($request->input('date'))
            ->orderByDesc('created_at');

        $fileName = 'contacts_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

        // Excelで文字化けしにくいようにBOMを付ける（課題ではこれが無難）
            fwrite($handle, "\xEF\xBB\xBF");

        // ヘッダー行
            fputcsv($handle, [
                'お名前',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせの種類',
                'お問い合わせ内容',
                '送信日時',
            ]);

        // 大量件数でも落ちにくいようにchunk
            $query->chunk(200, function ($contacts) use ($handle) {
                foreach ($contacts as $contact) {
                    $genderText = $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他');

                    fputcsv($handle, [
                        trim(($contact->last_name ?? '') . ' ' . ($contact->first_name ?? '')),
                        $genderText,
                        $contact->email ?? '',
                        $contact->tel ?? '',
                        $contact->address ?? '',
                        $contact->building ?? '',
                        optional($contact->category)->content ?? '',
                        $contact->detail ?? '',
                        (string) $contact->created_at,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    
    public function destroy(Request $request)
    {
        $id = $request->input('contact_id');

        Contact::findOrFail($id)->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('message', '削除しました');
    }
}
