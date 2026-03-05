<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessTypes = ['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'];
        $statuses = ['New Lead', 'Existing', 'Drop'];
        $callingStatuses = ['Call Answered', 'Not Answered', 'Busy', 'Switched Off'];

        return [
            'company_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'phone_1' => $this->faker->phoneNumber(),
            'phone_2' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'address' => $this->faker->address(),
            'lead_source' => $this->faker->randomElement(['Website', 'Reference', 'Cold Call', 'Social Media']),
            'business_type' => $this->faker->randomElement($businessTypes),
            'status' => $this->faker->randomElement($statuses),
            'calling_status' => $this->faker->randomElement($callingStatuses),
            'feedback' => $this->faker->sentence(),
            'assigned_to' => null, // Initially unassigned
        ];
    }
}
