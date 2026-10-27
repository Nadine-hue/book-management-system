<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // GET: Display all books and search by title or author
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get(), 200);
    }

    // POST: Create a book
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'publication_year' => 'required|integer|min:1|max:' . date('Y'),
        ]);

        $book = Book::create($data);

        return response()->json([
            'message' => 'Book created successfully.',
            'data' => $book,
        ], 201);
    }

    // GET: Display one book
    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    // PUT: Update a book
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'publication_year' => 'required|integer|min:1|max:' . date('Y'),
        ]);

        $book->update($data);

        return response()->json([
            'message' => 'Book updated successfully.',
            'data' => $book,
        ], 200);
    }

    // DELETE: Remove a book
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ], 200);
    }
}
