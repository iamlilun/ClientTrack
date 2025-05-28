<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Contact;

class ContactRepository
{
    /**
     * Get all contacts for a given client.
     *
     * @param Client $client
     * @return \Illuminate\Database\Eloquent\Collection<Contact>
     */
    public function getAllByClient(Client $client)
    {
        return $client->contacts()->latest()->get();
    }

    /**
     * Create a new contact for the specified client.
     *
     * @param Client $client
     * @param array $data
     * @return Contact
     */
    public function createForClient(Client $client, array $data): Contact
    {
        return $client->contacts()->create($data);
    }

    /**
     * Find a contact by its ID for a given client.
     *
     * @param Client $client
     * @param int $id
     * @return Contact|null
     */
    public function find(Client $client, int $id): ?Contact
    {
        return $client->contacts()->where('id', $id)->first();
    }

    /**
     * Update an existing contact.
     *
     * @param Contact $contact
     * @param array $data
     * @return Contact
     */
    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact;
    }

    /**
     * Delete a contact.
     *
     * @param Contact $contact
     * @return void
     */
    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}
