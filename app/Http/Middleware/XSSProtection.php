<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }

        $input = $request->all();

        //foreach ($input as $key => $value) {
        //    if (preg_match('/script/', $value)) {
        //        return response()->json(['error' => "Nous ne somme qu'une petite startup :/ Postule plutot chez nous pour nous aider à grimper."], 418);
        //    }
        //}

        array_walk_recursive($input, function (&$input) {
            $input = strip_tags($input);
        });

        $request->merge($input);

        return $next($request);
    }
}
