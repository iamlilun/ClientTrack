<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\ClientRepository;

class ClientService
{
    public function __construct(protected ClientRepository $repo) {}

    /**
     * List all clients for a given user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection<Client>
     */
    public function list(User $user)
    {
        return $this->repo->getAllByUser($user);
    }

    /**
     * Create a new client for the specified user.
     *
     * @param User $user
     * @param array $data
     * @return Client
     */
    public function create(User $user, array $data): Client
    {
        return $this->repo->createForUser($user, $data);
    }

    /**
     * Show a specific client owned by the user.
     *
     * @param User $user
     * @param int $id
     * @return Client
     * @throws AuthorizationException
     */
    public function show(User $user, int $id): Client
    {
        $client = $this->repo->findOwnedBy($user, $id);

        if (! $client) {
            throw new AuthorizationException('你無權存取這筆資料');
        }

        return $client;
    }

    /**
     * Update a specific client owned by the user.
     *
     * @param User $user
     * @param int $id
     * @param array $data
     * @return Client
     */
    public function update(User $user, int $id, array $data): Client
    {
        $client = $this->show($user, $id);
        return $this->repo->update($client, $data);
    }

    /**
     * Delete a specific client owned by the user.
     *
     * @param User $user
     * @param int $id
     * @return void
     */
    public function delete(User $user, int $id): void
    {
        $client = $this->show($user, $id);
        $this->repo->delete($client);
    }
}
