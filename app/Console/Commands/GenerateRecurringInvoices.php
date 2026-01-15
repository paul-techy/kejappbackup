<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generate-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring invoices based on their intervals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting recurring invoice generation...');

        // Get all active recurring invoices (parent invoices)
        $recurringInvoices = Invoice::where('is_recurring', true)
            ->whereNull('recurring_parent_id')
            ->get();

        $generatedCount = 0;

        foreach ($recurringInvoices as $parentInvoice) {
            // Check if recurring has ended
            if ($parentInvoice->recurring_end_date && Carbon::parse($parentInvoice->recurring_end_date)->isPast()) {
                continue;
            }

            // Determine next generation date based on interval
            $lastGenerated = $parentInvoice->last_generated_date 
                ? Carbon::parse($parentInvoice->last_generated_date)
                : Carbon::parse($parentInvoice->recurring_start_date);

            $today = Carbon::today();
            $startDate = Carbon::parse($parentInvoice->recurring_start_date);

            // Generate invoices for all missed periods
            $currentDate = $lastGenerated->copy();
            while (true) {
                $nextGenerationDate = $this->getNextGenerationDate($currentDate, $parentInvoice->recurring_interval);
                
                // Stop if next generation date is in the future
                if ($nextGenerationDate->gt($today)) {
                    break;
                }

                // Stop if recurring has ended
                if ($parentInvoice->recurring_end_date && $nextGenerationDate->gt(Carbon::parse($parentInvoice->recurring_end_date))) {
                    break;
                }

                // Check if invoice for this period already exists
                $existingInvoice = Invoice::where('recurring_parent_id', $parentInvoice->id)
                    ->where('invoice_month', $nextGenerationDate->format('Y-m-d'))
                    ->first();

                if (!$existingInvoice) {
                    $this->generateInvoice($parentInvoice, $nextGenerationDate);
                    $generatedCount++;

                    // Update last generated date
                    $parentInvoice->last_generated_date = $nextGenerationDate->format('Y-m-d');
                    $parentInvoice->save();
                }

                $currentDate = $nextGenerationDate;
            }
        }

        $this->info("Generated {$generatedCount} recurring invoice(s).");
        return Command::SUCCESS;
    }

    /**
     * Get the next generation date based on interval
     */
    private function getNextGenerationDate(Carbon $lastDate, string $interval): Carbon
    {
        switch ($interval) {
            case 'daily':
                return $lastDate->copy()->addDay();
            case 'monthly':
                return $lastDate->copy()->addMonth();
            case 'yearly':
                return $lastDate->copy()->addYear();
            default:
                return $lastDate->copy()->addMonth();
        }
    }

    /**
     * Generate a new invoice based on parent recurring invoice
     */
    private function generateInvoice(Invoice $parentInvoice, Carbon $generationDate)
    {
        // Get the latest invoice number
        $latestInvoice = Invoice::where('parent_id', $parentInvoice->parent_id)->latest()->first();
        $newInvoiceNumber = $latestInvoice ? $latestInvoice->invoice_id + 1 : $parentInvoice->invoice_id + 1;

        // Calculate end date based on interval
        $endDate = $this->calculateEndDate($generationDate, $parentInvoice->recurring_interval);

        // Create new invoice
        $newInvoice = new Invoice();
        $newInvoice->invoice_id = $newInvoiceNumber;
        $newInvoice->property_id = $parentInvoice->property_id;
        $newInvoice->unit_id = $parentInvoice->unit_id;
        $newInvoice->invoice_month = $generationDate->format('Y-m-d');
        $newInvoice->end_date = $endDate->format('Y-m-d');
        $newInvoice->notes = $parentInvoice->notes;
        $newInvoice->status = 'open';
        $newInvoice->parent_id = $parentInvoice->parent_id;
        $newInvoice->is_recurring = true;
        $newInvoice->recurring_interval = $parentInvoice->recurring_interval;
        $newInvoice->recurring_start_date = $parentInvoice->recurring_start_date;
        $newInvoice->recurring_end_date = $parentInvoice->recurring_end_date;
        $newInvoice->recurring_parent_id = $parentInvoice->id;
        $newInvoice->last_generated_date = $generationDate->format('Y-m-d');
        $newInvoice->save();

        // Copy invoice items from parent
        $parentItems = InvoiceItem::where('invoice_id', $parentInvoice->id)->get();
        foreach ($parentItems as $parentItem) {
            $newItem = new InvoiceItem();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->invoice_type = $parentItem->invoice_type;
            $newItem->amount = $parentItem->amount;
            $newItem->description = $parentItem->description;
            $newItem->save();
        }

        $this->info("Generated invoice #{$newInvoiceNumber} for property {$parentInvoice->property_id}, unit {$parentInvoice->unit_id}");
    }

    /**
     * Calculate end date based on interval
     */
    private function calculateEndDate(Carbon $startDate, string $interval): Carbon
    {
        switch ($interval) {
            case 'daily':
                return $startDate->copy()->addDay();
            case 'monthly':
                return $startDate->copy()->addMonth();
            case 'yearly':
                return $startDate->copy()->addYear();
            default:
                return $startDate->copy()->addMonth();
        }
    }
}
