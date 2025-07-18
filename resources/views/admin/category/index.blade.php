<x-layouts.admin :title="__('Category Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Category Admin</h1>
            <button id="openModalBtn"
                class="bg-primary p-1.5 hover:bg-primary/70 rounded-md font-semibold text-secondary">
                Create Category
            </button>
        </div>

        <!-- Category List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories ?? [] as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-900">
                                    <a href="/catalog?category={{ $category->slug }}" class="underline">
                                        {{ $category->slug }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->slug }}')"
                                        class="text-indigo-600 cursor-pointer hover:text-indigo-900 mr-3">
                                        Edit
                                    </button>
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                                        class="text-red-600 cursor-pointer hover:text-red-900">
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $category->id }}"
                                        action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No categories found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-4">
            <div class="fixed inset-0 transition-opacity bg-black/55" id="modalOverlay"></div>

            <div
                class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900">
                        Create New Category
                    </h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="categoryForm" action="{{ route('admin.category.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="">
                    <input type="hidden" id="categoryId" name="id" value="">

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Enter category name">
                    </div>

                    <input type="hidden" id="slugInput" name="slug">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Preview Slug
                        </label>
                        <div id="slugPreview"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-600">
                            -
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 text-sm font-medium text-secondary bg-primary rounded-md hover:bg-primary/70 focus:outline-none">
                            {{ __('Create Category') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Modal functionality
        const modal = document.getElementById('categoryModal');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const overlay = document.getElementById('modalOverlay');
        const nameInput = document.getElementById('name');
        const slugPreview = document.getElementById('slugPreview');
        const modalTitle = document.getElementById('modalTitle');
        const categoryForm = document.getElementById('categoryForm');
        const methodField = document.getElementById('methodField');
        const categoryIdField = document.getElementById('categoryId');
        const submitBtn = document.getElementById('submitBtn');

        let isEditMode = false;

        // Function to generate slug from name
        function generateSlug(str) {
            return str
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
        }

        // Update slug preview when name changes
        nameInput.addEventListener('input', function() {
            const slug = generateSlug(this.value);
            slugPreview.textContent = slug || '-';
            document.getElementById('slugInput').value = slug;
        });

        // Open modal for creating new category
        openBtn.addEventListener('click', () => {
            openModal(false);
        });

        // Close modal
        [closeBtn, cancelBtn, overlay].forEach(element => {
            element.addEventListener('click', () => {
                closeModal();
            });
        });

        // Function to open modal
        function openModal(editMode = false, categoryData = null) {
            isEditMode = editMode;
            modal.classList.remove('hidden');

            if (editMode && categoryData) {
                // Edit mode
                modalTitle.textContent = 'Edit Category';
                submitBtn.textContent = 'Update Category';
                submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
                submitBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');

                // Set form action and method for update
                categoryForm.action = `/admin/category/${categoryData.id}`;
                methodField.value = 'PUT';
                categoryIdField.value = categoryData.id;

                // Fill form with existing data
                nameInput.value = categoryData.name;
                slugPreview.textContent = categoryData.slug;
                document.getElementById('slugInput').value = categoryData.slug;
            } else {
                // Create mode
                modalTitle.textContent = 'Create New Category';
                submitBtn.textContent = 'Create Category';
                submitBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                submitBtn.classList.add('bg-green-500', 'hover:bg-green-600');

                // Set form action and method for create
                categoryForm.action = '{{ route('admin.category.store') }}';
                methodField.value = '';
                categoryIdField.value = '';

                // Reset form
                resetForm();
            }
        }

        // Function to close modal
        function closeModal() {
            modal.classList.add('hidden');
            resetForm();
        }

        // Function to reset form
        function resetForm() {
            nameInput.value = '';
            slugPreview.textContent = '-';
            document.getElementById('slugInput').value = '';
            isEditMode = false;
        }

        // Function to edit category
        function editCategory(categoryId, categoryName, categorySlug) {
            const categoryData = {
                id: categoryId,
                name: categoryName,
                slug: categorySlug
            };
            openModal(true, categoryData);
        }

        // Function to delete category with SweetAlert2
        function deleteCategory(categoryId, categoryName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete the category "${categoryName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${categoryId}`).submit();
                }
            });
        }
    </script>
</x-layouts.admin>
