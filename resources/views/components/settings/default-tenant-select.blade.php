<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <flux:select wire:model="industry" placeholder="Choose industry...">
        <flux:select.option>Photography</flux:select.option>
        <flux:select.option>Design services</flux:select.option>
        <flux:select.option>Web development</flux:select.option>
        <flux:select.option>Accounting</flux:select.option>
        <flux:select.option>Legal services</flux:select.option>
        <flux:select.option>Consulting</flux:select.option>
        <flux:select.option>Other</flux:select.option>
    </flux:select>
</div>
