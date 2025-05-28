<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\User;

class ClientRepository
{

    /**
     * getAllByUser
     * Retrieves all clients associated with a given user.
     * @param User $user The user whose clients are to be retrieved.
     * @return \Illuminate\Database\Eloquent\Collection<Client> A collection of clients associated with the user.
     */
    public function getAllByUser(User $user)
    {
        return $user->clients()->latest()->get();
    }

    /**
     * createForUser
     * Creates a new client for the specified user with the provided data.
     * @param User $user The user for whom the client is being created.
     * @param array $data An associative array containing the client data.
     * @return Client The newly created client instance.
     */
    public function createForUser(User $user, array $data): Client
    {
        return $user->clients()->create($data);
    }

    /**
     * findOwnedBy
     * Finds a client owned by the specified user.
     * @param User $user The user who owns the client.
     * @param int $clientId The ID of the client to find.
     * @return Client|null The client if found, or null if not found.
     */
    public function findOwnedBy(User $user, int $clientId): ?Client
    {
        return $user->clients()->where('id', $clientId)->first();
    }

    /**
     * update
     * Updates the specified client with the provided data.
     * @param Client $client The client to be updated.
     * @param array $data An associative array containing the updated client data.
     * @return Client The updated client instance.
     */
    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client;
    }

    /**
     * delete
     * Deletes the specified client.
     * @param Client $client The client to be deleted.
     * @return void
     */
    public function delete(Client $client): void
    {
        $client->delete();
    }
}
