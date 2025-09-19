<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute,
    ) {
        $query = Event::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        $query->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage,
    ) {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false,
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = Event::where("id", $id);

        return $query->first();
    }

    public function create(
        array $data,
    ) {
        DB::beginTransaction();

        try {
            $Event = new Event;

            $Event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            $Event->name = $data['name'];
            $Event->description = $data['description'];
            $Event->price = $data['price'];
            $Event->date = $data['date'];
            $Event->time = $data['time'];

            if (isset($data['is_active'])) {
                $Event->is_active = $data['is_active'];
            }

            $Event->save();

            DB::commit();

            return $Event;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data,
    ) {
        DB::beginTransaction();

        try {
            $Event = Event::find($id);

            $Event->name = $data['name'];
            $Event->description = $data['description'];
            $Event->price = $data['price'];
            $Event->date = $data['date'];
            $Event->time = $data['time'];

            if (isset($data['thumbnail'])) {
                $Event->thumbnail = $data['thumbnail']->store('assets/events', 'public');
            }

            if (isset($data['is_active'])) {
                $Event->is_active = $data['is_active'];
            }

            $Event->save();

            DB::commit();

            return $Event;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function delete(
        string $id,
    ) {
        DB::beginTransaction();

        try {
            $Event = Event::find($id);
            $Event->delete();

            DB::commit();

            return $Event;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
