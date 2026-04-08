<?php

namespace App\Services;

use App\Http\Controllers\MikrotikController;

/**
 * MikrotikSetupService
 *
 * Thin facade over MikrotikController.
 * Every add*() method accepts an optional $editId:
 *   - null  → runs the "add" command (create new)
 *   - string → runs the "set numbers=$editId" command (update existing)
 */
class MikrotikSetupService
{
    public function __construct(protected MikrotikController $ctrl) {}

    // ─── IP Addresses ─────────────────────────────────────────────────────────

    public function getIpAddresses(string $routerName): array
    {
        return $this->ctrl->getIpAddresses($routerName);
    }

    /** Pass $editId to update an existing entry instead of adding a new one. */
    public function addIpAddress(string $routerName, string $address, string $interface, ?string $comment = null, ?string $editId = null): string
    {
        return $this->ctrl->addIpAddress($routerName, $address, $interface, $comment, $editId);
    }

    public function removeIpAddress(string $routerName, string $address): string
    {
        return $this->ctrl->removeIpAddress($routerName, $address);
    }

    // ─── IP Pools ─────────────────────────────────────────────────────────────

    public function getIpPools(string $routerName): array
    {
        return $this->ctrl->getIpPools($routerName);
    }

    /** Pass $editId to update an existing pool instead of adding a new one. */
    public function addIpPool(string $routerName, string $name, string $ranges, ?string $nextPool = null, ?string $editId = null): string
    {
        return $this->ctrl->addIpPool($routerName, $name, $ranges, $nextPool, $editId);
    }

    public function removeIpPool(string $routerName, string $name): string
    {
        return $this->ctrl->removeIpPool($routerName, $name);
    }

    // ─── DHCP Server ──────────────────────────────────────────────────────────

    public function getDhcpServers(string $routerName): array
    {
        return $this->ctrl->getDhcpServers($routerName);
    }

    public function getDhcpNetworks(string $routerName): array
    {
        return $this->ctrl->getDhcpNetworks($routerName);
    }

    /** Pass $editId to update an existing DHCP server instead of adding a new one. */
    public function addDhcpServer(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addDhcpServer($routerName, $p, $editId);
    }

    public function removeDhcpServer(string $routerName, string $name): string
    {
        return $this->ctrl->removeDhcpServer($routerName, $name);
    }

    public function toggleDhcpServer(string $routerName, string $name, bool $enable): string
    {
        return $this->ctrl->toggleDhcpServer($routerName, $name, $enable);
    }

    /** Pass $editId to update an existing DHCP network instead of adding a new one. */
    public function addDhcpNetwork(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addDhcpNetwork($routerName, $p, $editId);
    }

    public function removeDhcpNetwork(string $routerName, string $address): string
    {
        return $this->ctrl->removeDhcpNetwork($routerName, $address);
    }

    // ─── Interfaces ───────────────────────────────────────────────────────────

    public function getInterfaces(string $routerName): array
    {
        return $this->ctrl->getInterfaces($routerName);
    }

    public function toggleInterface(string $routerName, string $name, bool $enable): string
    {
        return $this->ctrl->toggleInterface($routerName, $name, $enable);
    }

    // ─── VLANs ────────────────────────────────────────────────────────────────

    public function getVlans(string $routerName): array
    {
        return $this->ctrl->getVlans($routerName);
    }

    /** Pass $editId to update an existing VLAN instead of adding a new one. */
    public function addVlan(string $routerName, string $name, int $vlanId, string $interface, ?string $comment = null, ?string $editId = null): string
    {
        return $this->ctrl->addVlan($routerName, $name, $vlanId, $interface, $comment, $editId);
    }

    public function removeVlan(string $routerName, string $name): string
    {
        return $this->ctrl->removeVlan($routerName, $name);
    }

    // ─── Bridges ──────────────────────────────────────────────────────────────

    public function getBridges(string $routerName): array
    {
        return $this->ctrl->getBridges($routerName);
    }

    public function getBridgePorts(string $routerName): array
    {
        return $this->ctrl->getBridgePorts($routerName);
    }

    public function addBridge(string $routerName, string $name, ?string $comment = null): string
    {
        return $this->ctrl->addBridge($routerName, $name, $comment);
    }

    public function removeBridge(string $routerName, string $name): string
    {
        return $this->ctrl->removeBridge($routerName, $name);
    }

    // ─── PPPoE Server ─────────────────────────────────────────────────────────

    public function getPppoeServers(string $routerName): array
    {
        return $this->ctrl->getPppoeServers($routerName);
    }

    /** Pass $editId to update an existing PPPoE server instead of adding a new one. */
    public function addPppoeServer(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addPppoeServer($routerName, $p, $editId);
    }

    public function removePppoeServer(string $routerName, string $name): string
    {
        return $this->ctrl->removePppoeServer($routerName, $name);
    }

    // ─── PPP Profiles & Secrets ───────────────────────────────────────────────

    public function getPppProfiles(string $routerName): array
    {
        return $this->ctrl->getPppProfiles($routerName);
    }

    public function getPppSecrets(string $routerName): array
    {
        return $this->ctrl->getPppSecrets($routerName);
    }

    /** Pass $editId to update an existing PPP secret instead of adding a new one. */
    public function addPppSecret(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addPppSecret($routerName, $p, $editId);
    }

    public function removePppSecret(string $routerName, string $name): string
    {
        return $this->ctrl->deletePppSecret($routerName, $name);
    }

    public function getActivePppSessions(string $routerName): array
    {
        return $this->ctrl->getActivePppSessions($routerName);
    }

    // ─── Hotspot ──────────────────────────────────────────────────────────────

    public function getHotspotServers(string $routerName): array
    {
        return $this->ctrl->getHotspotServers($routerName);
    }

    public function getHotspotProfiles(string $routerName): array
    {
        return $this->ctrl->getHotspotProfiles($routerName);
    }

    public function getHotspotUsers(string $routerName): array
    {
        return $this->ctrl->getHotspotUsers($routerName);
    }

    public function getHotspotUserProfiles(string $routerName): array
    {
        return $this->ctrl->getHotspotUserProfiles($routerName);
    }

    public function getHotspotActiveSessions(string $routerName): array
    {
        return $this->ctrl->getHotspotActiveSessions($routerName);
    }

    /** Pass $editId to update an existing hotspot user instead of adding a new one. */
    public function addHotspotUser(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addHotspotUser($routerName, $p, $editId);
    }

    public function removeHotspotUser(string $routerName, string $name): string
    {
        return $this->ctrl->removeHotspotUser($routerName, $name);
    }

    /** Pass $editId to update an existing hotspot user profile instead of adding a new one. */
    public function addHotspotUserProfile(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addHotspotUserProfile($routerName, $p, $editId);
    }

    public function removeHotspotUserProfile(string $routerName, string $name): string
    {
        return $this->ctrl->removeHotspotUserProfile($routerName, $name);
    }

    // ─── RADIUS ───────────────────────────────────────────────────────────────

    public function getRadiusServers(string $routerName): array
    {
        return $this->ctrl->getRadiusServers($routerName);
    }

    /** Pass $editId to update an existing RADIUS server instead of adding a new one. */
    public function addRadiusServer(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addRadiusServer($routerName, $p, $editId);
    }

    public function removeRadiusServer(string $routerName, string $address): string
    {
        return $this->ctrl->removeRadiusServer($routerName, $address);
    }

    public function toggleRadiusServer(string $routerName, string $address, bool $enable): string
    {
        return $this->ctrl->toggleRadiusServer($routerName, $address, $enable);
    }

    // ─── Firewall ─────────────────────────────────────────────────────────────

    public function getFirewallFilter(string $routerName): array
    {
        return $this->ctrl->getFirewallFilter($routerName);
    }

    public function getFirewallNat(string $routerName): array
    {
        return $this->ctrl->getFirewallNat($routerName);
    }

    public function getFirewallMangle(string $routerName): array
    {
        return $this->ctrl->getFirewallMangle($routerName);
    }

    public function getAddressLists(string $routerName): array
    {
        return $this->ctrl->getAddressLists($routerName);
    }

    /** Pass $editId to update an existing filter rule instead of adding a new one. */
    public function addFirewallFilter(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addFirewallFilter($routerName, $p, $editId);
    }

    /** Pass $editId to update an existing NAT rule instead of adding a new one. */
    public function addFirewallNat(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addFirewallNat($routerName, $p, $editId);
    }

    /**
     * Remove a firewall rule by table ('filter'|'nat'|'mangle') and index number.
     */
    public function removeFirewallRule(string $routerName, string $table, int $index): string
    {
        return match ($table) {
            'filter' => $this->ctrl->removeFirewallFilter($routerName, $index),
            'nat' => $this->ctrl->removeFirewallNat($routerName, $index),
            'mangle' => $this->ctrl->removeFirewallMangle($routerName, $index),
            default => throw new \InvalidArgumentException("Unknown firewall table: {$table}"),
        };
    }

    /**
     * Toggle a firewall rule by table ('filter'|'nat'|'mangle') and index number.
     */
    public function toggleFirewallRule(string $routerName, string $table, int $index, bool $enable): string
    {
        return match ($table) {
            'filter' => $this->ctrl->toggleFirewallFilter($routerName, $index, $enable),
            'nat' => $this->ctrl->toggleFirewallNat($routerName, $index, $enable),
            'mangle' => $this->ctrl->toggleFirewallMangle($routerName, $index, $enable),
            default => throw new \InvalidArgumentException("Unknown firewall table: {$table}"),
        };
    }

    /** Pass $editId to update an existing address-list entry instead of adding a new one. */
    public function addAddressList(string $routerName, string $list, string $address, ?string $comment = null, ?string $editId = null): string
    {
        return $this->ctrl->addAddressList($routerName, $list, $address, $comment, $editId);
    }

    public function removeAddressList(string $routerName, string $list, string $address): string
    {
        return $this->ctrl->removeAddressList($routerName, $list, $address);
    }

    // ─── Queues ───────────────────────────────────────────────────────────────

    public function getSimpleQueues(string $routerName): array
    {
        return $this->ctrl->getSimpleQueues($routerName);
    }

    /** Pass $editId to update an existing simple queue instead of adding a new one. */
    public function addSimpleQueue(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addSimpleQueue($routerName, $p, $editId);
    }

    public function removeSimpleQueue(string $routerName, string $name): string
    {
        return $this->ctrl->removeSimpleQueue($routerName, $name);
    }

    public function toggleSimpleQueue(string $routerName, string $name, bool $enable): string
    {
        return $this->ctrl->toggleSimpleQueue($routerName, $name, $enable);
    }

    public function getQueueTree(string $routerName): array
    {
        return $this->ctrl->getQueueTree($routerName);
    }

    /** Pass $editId to update an existing queue tree entry instead of adding a new one. */
    public function addQueueTree(string $routerName, array $p, ?string $editId = null): string
    {
        return $this->ctrl->addQueueTree($routerName, $p, $editId);
    }

    public function removeQueueTree(string $routerName, string $name): string
    {
        return $this->ctrl->removeQueueTree($routerName, $name);
    }

    public function getQueueTypes(string $routerName): array
    {
        return $this->ctrl->getQueueTypes($routerName);
    }

    // ─── VPN ──────────────────────────────────────────────────────────────────

    public function getL2tpStatus(string $routerName): array
    {
        return $this->ctrl->getL2tpStatus($routerName);
    }

    public function setL2tpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2', ?string $ipsecSecret = null): string
    {
        return $this->ctrl->setL2tpServer($routerName, $enabled, $profile, $auth, $ipsecSecret);
    }

    public function getPptpStatus(string $routerName): array
    {
        return $this->ctrl->getPptpStatus($routerName);
    }

    public function setPptpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2'): string
    {
        return $this->ctrl->setPptpServer($routerName, $enabled, $profile, $auth);
    }

    public function getSstpStatus(string $routerName): array
    {
        return $this->ctrl->getSstpStatus($routerName);
    }

    public function setSstpServer(string $routerName, bool $enabled, string $profile = 'default', int $port = 443): string
    {
        return $this->ctrl->setSstpServer($routerName, $enabled, $profile, $port);
    }
}
