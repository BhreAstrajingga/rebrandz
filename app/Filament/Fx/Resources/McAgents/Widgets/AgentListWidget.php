<?php

namespace App\Filament\Fx\Resources\McAgents\Widgets;

use App\Models\McAgent;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AgentListWidget extends TableWidget
{
	public $selectedAgentId;

    public function mount($selectedAgentId = null)
    {
        $this->selectedAgentId = null;
    }

    protected $listeners = [
        'agentSelected' => 'setAgentSelected',
        'agent-created' => 'refreshAgent',
        'agent-updated' => 'refreshAgent',
        'agent-deleted' => 'refreshAgent',
    ];

    public function setAgentSelected($payload = null)
    {
        $this->selectedAgentId = $payload['id'] ?? null;
    }

    public function refreshAgent(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedAgentId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => McAgent::query())
			->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedAgentId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : 'cursor-pointer';
            })
            ->columns([
                TextColumn::make('agent_code')
					->label('Code')
					->searchable()
					 ->action(function (McAgent $record) {
                        $this->dispatch('agentSelected', ['id' => $record->id]);
                    }),
                TextColumn::make('name')
					->label('Agent')
					->searchable()
					 ->action(function (McAgent $record) {
                        $this->dispatch('agentSelected', ['id' => $record->id]);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
					->label('New Agent')
					->icon('heroicon-o-plus')
					->size('sm')->action(function () {
                        $this->dispatch('agentSelected', null);
                    }),
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
