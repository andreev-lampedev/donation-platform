<?php

namespace App\Repositories;

use App\Contracts\DonationRepositoryInterface;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;

class DonationRepository implements DonationRepositoryInterface
{
    /**
     * Model.
     * 
     * @var Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * DonationRepository constructor.
     *
     * @param  Donation $model
     * @return void
     */
    public function __construct(Donation $model)
    {
        $this->model = $model;
    }

    /**
     * Get all donations.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Create new donation.
     *
     * @param  array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update donation.
     *
     * @param  array $data
     * @param  int $id
     * @return void
     */
    public function update(array $data, int $id): void
    {
        $this->model->find($id)->update($data);
    }

    /**
     * Delete donation by id.
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->model->destroy($id);
    }

    /**
     * Show donation by id.
     *
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get the top donator.
     *
     * @return Model
     */
    public function getTopDonator(): Model
    {
        return $this->model->orderBy('amount', 'desc')->select('amount', 'name')->first();
    }

    /**
     * Get sum of donations per day.
     *
     * @return Decimal
     */
    public function getDayAmount(): Decimal
    {
        return $this->model->whereDay('created_at', Carbon::now()->day)->sum('amount');
    }

    /**
     * Get sum of donations per month.
     *
     * @return Decimal
     */
    public function getMonthAmount(): Decimal
    {
        return $this->model->whereMonth('created_at', Carbon::now()->month)->sum('amount');
    }

    /**
     * Get amount of donations by day.
     *
     * @return array
     */
    public function getAmountByDay(): array
    {
        return $this->model->select('created_at', 'amount')->get()->groupBy(function ($row) {
            return Carbon::parse($row->created_at)->format('d.m.Y');
        })->map(function ($row) {
            return $row->sum('amount');
        });
    }

    /**
     * Paginate donations ordered by descend amount.
     *
     * @param  int $page
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateOrderedDonations(int $page): LengthAwarePaginator
    {
        return $this->model->orderByDesc('amount')->paginate(10);
    }
}
