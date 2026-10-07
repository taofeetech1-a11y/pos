<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::with('productSupplies.product')
            ->latest()
            ->get();

        return view('pages.supply.suppliers', compact('suppliers'));
    }


    public function show($id)
    {
        $supplier = Supplier::with(['productSupplies.product'])
            ->findOrFail($id);

        return response()->json($supplier);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $manager = new ImageManager(new Driver());
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $img = $manager->read($image);
                $img->resize(300, 300);
                $img->save(public_path("uploads/suppliers/$name_gen"));

                $imagePath = "uploads/suppliers/$name_gen";
            }

            Supplier::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'image' => $imagePath
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier created successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if ($supplier->image && file_exists(public_path($supplier->image))) {
                    unlink(public_path($supplier->image));
                }

                $image = $request->file('image');
                $manager = new ImageManager(new Driver());
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $img = $manager->read($image);
                $img->resize(300, 300);
                $img->save(public_path("uploads/suppliers/$name_gen"));

                $validated['image'] = "uploads/suppliers/$name_gen";
            }

            $supplier->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Delete image
            if ($supplier->image && file_exists(public_path($supplier->image))) {
                unlink(public_path($supplier->image));
            }

            $supplier->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete supplier: ' . $e->getMessage()
            ], 500);
        }
    }
}
