<?php

namespace App\Http\Controllers\System;

use App\Helpers\ActivityLogger;
use App\Helpers\UrlManager;
use App\Http\Controllers\Controller;
use App\Models\UrlApi;
use App\Models\UrlAuthenticated;
use App\Models\UrlGroup;
use App\Models\UrlPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class UrlManagementController extends Controller
{
    public function index()
    {
        $groups = UrlGroup::with(['authenticatedMenus' => function($q) {
            $q->whereNull('parent_id')->orderBy('order')->with('children');
        }, 'publicLinks.children', 'apis'])
            ->orderBy('order')
            ->get();

        $permissions = Permission::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('system/Url/Index', [
            'groups' => $groups,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:authenticated,public,api',
            'group_id' => 'required|exists:url_groups,id',
            'name' => 'required|string',
            'url' => 'required|string',
        ]);

        $data = $request->except(['type']);
        $model = null;

        if ($request->type === 'authenticated') {
            $model = UrlAuthenticated::create($data);
        } elseif ($request->type === 'public') {
            $model = UrlPublic::create($data);
        } elseif ($request->type === 'api') {
            $model = UrlApi::create($data);
        }

        if ($model) {
            ActivityLogger::log('Created new URL item: ' . $request->name, 'system');
            UrlManager::clearMenuCache($request->user()->id);
        }

        return back()->with('success', 'Data URL berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:authenticated,public,api',
            'name' => 'required|string',
            'url' => 'required|string',
        ]);

        $model = null;

        if ($request->type === 'authenticated') {
            $model = UrlAuthenticated::findOrFail($id);
        } elseif ($request->type === 'public') {
            $model = UrlPublic::findOrFail($id);
        } elseif ($request->type === 'api') {
            $model = UrlApi::findOrFail($id);
        }

        if ($model) {
            $model->update($request->except(['type']));
            ActivityLogger::log('Updated URL item: ' . $model->name, 'system');
            UrlManager::clearMenuCache($request->user()->id);
        }

        return back()->with('success', 'Data URL berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate(['type' => 'required|in:authenticated,public,api']);

        $model = null;

        if ($request->type === 'authenticated') {
            $model = UrlAuthenticated::findOrFail($id);
        } elseif ($request->type === 'public') {
            $model = UrlPublic::findOrFail($id);
        } elseif ($request->type === 'api') {
            $model = UrlApi::findOrFail($id);
        }

        if ($model) {
            $model->delete();
            ActivityLogger::log('Deleted URL item: ' . $model->name, 'system', null, [], 'danger');
            UrlManager::clearMenuCache($request->user()->id);
        }

        return back()->with('success', 'Data URL berhasil dihapus.');
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:url_groups,name',
            'section' => 'required|in:public,authenticated,system',
        ]);

        UrlGroup::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'section' => $request->section,
            'order' => UrlGroup::max('order') + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Group URL berhasil dibuat.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'groups' => 'required|array',
            'groups.*.id' => 'required|integer',
            'groups.*.order' => 'required|integer',
            'groups.*.items' => 'present|array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->groups as $groupData) {
                UrlGroup::where('id', $groupData['id'])->update(['order' => $groupData['order']]);

                if (!empty($groupData['items'])) {
                    foreach ($groupData['items'] as $itemData) {
                        UrlAuthenticated::where('id', $itemData['id'])->update([
                            'order' => $itemData['order'],
                            'group_id' => $itemData['group_id'],
                            'parent_id' => $itemData['parent_id'] ?? null,
                        ]);
                    }
                }
            }
        });

        ActivityLogger::log('Reordered menu structure', 'system');
        UrlManager::clearMenuCache($request->user()->id);

        return back()->with('success', 'Struktur menu berhasil diperbarui.');
    }
}
