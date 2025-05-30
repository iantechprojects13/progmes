<?php

use App\Models\ProgramAssignmentModel;
use App\Models\RoleModel;
use App\Models\AdminSettingsModel;
use App\Models\ProgramModel;
use App\Models\InstitutionModel;
use App\Models\InstitutionProgramModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

//item per page helper function
if (!function_exists('sanitizePerPage')) {
    function sanitizePerPage($value, $userId = null, $default = 25) {
        $cacheKey = 'user_per_page' . ($userId ? "_{$userId}" : '_guest');
        
        // If a value is provided in the request, sanitize and cache it
        if ($value !== null) {
            $sanitizedValue = (is_numeric($value) && $value >= 25 && $value <= 2000) ? (int) $value : $default;
            // Store the value in cache
            cache()->put($cacheKey, $sanitizedValue, now()->addMonths(3));
            return $sanitizedValue;
        }
        
        // If no value provided, try to get from cache or use default
        return cache()->remember($cacheKey, now()->addMonths(3), function() use ($default) {
            return $default;
        });
    }
}

//academic year helper function
if(!function_exists('getAcademicYear')) {
    function getAcademicYear($value, $userId = null, $default = null) {
        // Get default academic year from settings if not provided
        if ($default === null) {
            $default = AdminSettingsModel::where('setting_key', 'default_academic_year')
                ->value('setting_value');
        }
        
        $cacheKey = 'user_academic_year' . ($userId ? "_{$userId}" : '_guest');
        
        // If a value is provided in the request, cache it
        if ($value !== null) {
            // Store the value in cache
            cache()->put($cacheKey, $value, now()->addMonths(3));
            return $value;
        }
        
        // If no value provided, try to get from cache or use default
        return cache()->remember($cacheKey, now()->addMonths(3), function() use ($default) {
            return $default;
        });
    }
}


/**
 * Get discipline IDs for the authenticated user
 * 
 * @param int|null $userId User ID (defaults to authenticated user)
 * @return array Array of discipline IDs
 */
function getUserDisciplineIds($userId = null)
{
    $userId = $userId ?? Auth::id();
    
    $disciplines = RoleModel::where('userId', $userId)
        ->where('isActive', 1)
        ->with('discipline')
        ->get();
        
    return $disciplines->pluck('discipline.id')->toArray();
}

/**
 * Get program IDs assigned to the user
 * 
 * @param int|null $userId User ID (defaults to authenticated user)
 * @return array Array of assigned program IDs
 */
function getUserAssignedProgramIds($userId = null)
{
    $userId = $userId ?? Auth::id();
    
    return ProgramAssignmentModel::where('userId', $userId)
        ->pluck('programId')
        ->toArray();
}

/**
 * Get a query constraint for filtering by programs assigned to an Education Supervisor.
 *
 * @param array $assignedProgramIds Array of program IDs assigned to the supervisor.
 * @return \Closure
 */
function supervisorAssignedPrograms(string $relationPath = 'program', array $assignedProgramIds)
{
    return function ($query) use ($relationPath, $assignedProgramIds) {
        $query->whereHas($relationPath, function ($q) use ($assignedProgramIds) {
            $q->whereIn('programId', $assignedProgramIds);
        });
    };
}

/**
 * Apply user search query to a query builder
 * 
 * @param Builder $query The query builder to apply the search to
 * @param string $searchTerm The search term to look for
 * @param bool $useOr Whether to use orWhere instead of where for the outer condition
 * @return Builder The modified query builder
 */
function applyUserSearch(Builder $query, string $searchTerm, bool $useOr = false)
{
    $method = $useOr ? 'orWhere' : 'where';
    
    return $query->$method(function ($searchQuery) use ($searchTerm) {
        $searchQuery->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('role', 'like', '%' . $searchTerm . '%');
    });
}

/**
 * Apply discipline search query to a query builder
 * 
 * @param Builder $query The query builder to apply the search to
 * @param string $searchTerm The search term to look for
 * @param string $relationPath The path to the discipline relation (e.g., 'evaluation_form.discipline')
 * @param bool $useOr Whether to use orWhereHas instead of whereHas
 * @return Builder The modified query builder
 */
function applyDisciplineSearch(Builder $query, string $searchTerm, string $relationPath, bool $useOr = false)
{
    $method = $useOr ? 'orWhereHas' : 'whereHas';
    
    return $query->$method($relationPath, function ($searchQuery) use ($searchTerm) {
        $searchQuery->where('discipline', 'like', '%' . $searchTerm . '%');
    });
}



/**
 * Apply program search query to a query builder
 * 
 * @param Builder $query The query builder to apply the search to
 * @param string $searchTerm The search term to look for
 * @param string $relationPath The path to the program relation (e.g., 'institution_program.program')
 * @param bool $useOr Whether to use orWhereHas instead of whereHas
 * @return Builder The modified query builder
 */
function applyProgramSearch(Builder $query, string $searchTerm, string $relationPath, bool $useOr = false)
{
    $method = $useOr ? 'orWhereHas' : 'whereHas';
    
    return $query->$method($relationPath, function ($searchQuery) use ($searchTerm) {
        $searchQuery->where('program', 'like', '%' . $searchTerm . '%')
                    ->orWhere('major', 'like', '%' . $searchTerm . '%');
    });
}


/**
 * Apply institution search query to a query builder
 * 
 * @param Builder $query The query builder to apply the search to
 * @param string $searchTerm The search term to look for
 * @param string $relationPath The path to the institution relation (e.g., 'institution_program.institution')
 * @return Builder The modified query builder
 */
function applyInstitutionSearch(Builder $query, string $searchTerm, string $relationPath, bool $useOr = false)
{
    $method = $useOr ? 'orWhereHas' : 'whereHas';

    return $query->$method($relationPath, function ($searchQuery) use ($searchTerm) {
        $searchQuery->where('name', 'like', '%' . $searchTerm . '%');
    });
}

/**
 * Apply combined program and institution search to a query builder
 * 
 * @param Builder $query The query builder to apply the search to
 * @param string $searchTerm The search term to look for
 * @param string $programRelationPath The path to the program relation
 * @param string $institutionRelationPath The path to the institution relation
 * @return Builder The modified query builder
 */
function applyProgramAndInstitutionSearch(Builder $query, string $searchTerm, string $programRelationPath, string $institutionRelationPath)
{   
    return $query->where(function ($q) use ($searchTerm, $programRelationPath, $institutionRelationPath) {
        $q->whereHas($programRelationPath, function ($searchQuery) use ($searchTerm) {
            $searchQuery->where('program', 'like', '%' . $searchTerm . '%')
                        ->orWhere('major', 'like', '%' . $searchTerm . '%');
        })
        ->orWhereHas($institutionRelationPath, function ($searchQuery) use ($searchTerm) {
            $searchQuery->where('name', 'like', '%' . $searchTerm . '%');
        });
    });
}

if (!function_exists('getProgress')) {
    /**
     * Calculate progress metrics for an evaluation tool
     * 
     * @param mixed $evaluationTool The evaluation tool model with relationships loaded
     * @return array Returns [complied_count, not_complied_count, not_applicable_count, progress_percentage]
     */
    function getProgress($evaluationTool)
    {
        $totalItems = $evaluationTool->item->count();
        $complied = $evaluationTool->complied->count();
        $notComplied = $evaluationTool->not_complied->count();
        $notApplicable = $evaluationTool->not_applicable->count();
        
        $progressPercentage = $totalItems > 0
            ? intval(round((($complied + $notComplied + $notApplicable) / $totalItems) * 100))
            : 0;

        // $complianceRate = $evaluationTool->complied->count() + $evaluationTool->not_complied->count() > 0
        //             ? intval(round(($evaluationTool->complied->count() / ($evaluationTool->complied->count() + $evaluationTool->not_complied->count())) * 100))
        //             : 0;
            
        return [$complied, $notComplied, $notApplicable, $progressPercentage];
    }

    function getSupervisorInstitutionIds($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $assignedProgramIds = getUserAssignedProgramIds($userId);
        $institutionIds = InstitutionProgramModel::whereIn('programId', $assignedProgramIds)
            ->pluck('institutionId')
            ->toArray();
        return InstitutionModel::whereIn('id', $institutionIds)
            ->pluck('id')
            ->toArray();
    }
}