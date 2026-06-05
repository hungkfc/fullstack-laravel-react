<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // 1. Lấy danh sách sách kèm thông tin tác giả
    public function index() {
        return response()->json(Book::with('author')->latest()->get(), 200);
    }

    // 2. Thêm mới sách + Upload ảnh
    public function store(Request $request) {
        $validated = $request->validate([
            'author_id'   => 'required|exists:authors,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('cover_image')) {
            // Lưu vào thư mục public/books trong Docker
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image'] = Storage::url($path);
        }

        $book = Book::create($validated);
        return response()->json($book, 201);
    }

    // 3. Xem chi tiết 1 cuốn sách
    public function show(Book $book) {
        return response()->json($book->load('author'), 200);
    }

    // 4. Cập nhật sách
    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);
        return response()->json($book, 200);
    }

    // 5. Xóa sách
    public function destroy(Book $book) {
        if ($book->cover_image) {
            // Xóa file ảnh cũ để tránh rác server
            $oldPath = str_replace('/storage/', '', $book->cover_image);
            Storage::disk('public')->delete($oldPath);
        }
        $book->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}