`disabledMatchers` accepts a mix of matcher shapes. This story combines a `{ before }` matcher (all past dates), a `{ dayOfWeek }` matcher (Sun/Sat) and a predicate function (the 15th of any month).

<!-- Blade port: matcher objects and predicate functions have no server-side analogue, so this port replaces `disabledMatchers` with a flat `disabledDays` array of `Y-m-d` strings. The story precomputes the three rules above for the month on screen. -->
