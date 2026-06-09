<?php

namespace App\Livewire\Admin;

use App\Models\IspExpense;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseManager extends Component
{
    use WithPagination;

    // Form fields
    public string $category     = 'item_purchase';
    public string $title        = '';
    public string $description  = '';
    public string $amount       = '';
    public string $expense_date = '';
    public string $reference_no = '';

    // Edit state
    public ?int $editId = null;

    // Filters
    public string $filterCategory = '';
    public string $filterMonth    = '';
    public string $filterYear     = '';

    public bool $showModal = false;

    public function mount(): void
    {
        $this->expense_date  = now()->format('Y-m-d');
        $this->filterMonth   = (string) now()->month;
        $this->filterYear    = (string) now()->year;
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'category', 'title', 'description', 'amount', 'reference_no']);
        $this->category     = 'item_purchase';
        $this->expense_date = now()->format('Y-m-d');
        $this->showModal    = true;
    }

    public function openEdit(int $id): void
    {
        $expense = IspExpense::findOrFail($id);
        $this->editId       = $expense->id;
        $this->category     = $expense->category;
        $this->title        = $expense->title;
        $this->description  = $expense->description ?? '';
        $this->amount       = (string) $expense->amount;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->reference_no = $expense->reference_no ?? '';
        $this->showModal    = true;
    }

    public function save(): void
    {
        $this->validate([
            'category'     => 'required|in:item_purchase,raw_bill,employee_salary,miscellaneous',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'reference_no' => 'nullable|string|max:100',
        ]);

        $data = [
            'category'     => $this->category,
            'title'        => $this->title,
            'description'  => $this->description ?: null,
            'amount'       => $this->amount,
            'expense_date' => $this->expense_date,
            'reference_no' => $this->reference_no ?: null,
            'added_by'     => auth()->id(),
        ];

        if ($this->editId) {
            IspExpense::findOrFail($this->editId)->update($data);
            flash()->success('Expense updated successfully.');
        } else {
            IspExpense::create($data);
            flash()->success('Expense added successfully.');
        }

        $this->showModal = false;
        $this->reset(['editId', 'category', 'title', 'description', 'amount', 'reference_no']);
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        IspExpense::findOrFail($id)->delete();
        flash()->success('Expense deleted.');
    }

    public function updatedFilterCategory(): void { $this->resetPage(); }
    public function updatedFilterMonth(): void    { $this->resetPage(); }
    public function updatedFilterYear(): void     { $this->resetPage(); }

    public function render()
    {
        $query = IspExpense::with('addedBy')->orderBy('expense_date', 'desc');

        if ($this->filterCategory) {
            $query->byCategory($this->filterCategory);
        }
        if ($this->filterMonth && $this->filterYear) {
            $query->byMonth((int) $this->filterMonth, (int) $this->filterYear);
        } elseif ($this->filterYear) {
            $query->whereYear('expense_date', $this->filterYear);
        }

        $expenses = $query->paginate(20);

        // Totals for current filter
        $totalQuery = IspExpense::query();
        if ($this->filterCategory) {
            $totalQuery->byCategory($this->filterCategory);
        }
        if ($this->filterMonth && $this->filterYear) {
            $totalQuery->byMonth((int) $this->filterMonth, (int) $this->filterYear);
        } elseif ($this->filterYear) {
            $totalQuery->whereYear('expense_date', $this->filterYear);
        }

        $totals = $totalQuery
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $grandTotal = $totals->sum();

        $categories  = IspExpense::$categories;
        $years       = range(now()->year, now()->year - 4);
        $months      = [
            1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
            7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December',
        ];

        return view('livewire.admin.expense-manager', compact(
            'expenses', 'totals', 'grandTotal', 'categories', 'years', 'months'
        ))->layout('layouts.app');
    }
}
