BubbleSort Simulation
===========

PROBLEM: Write a simulation of bubble sort algorithm.

------------

GOALS:

1. Develop a Drupal 8 module that is a simulation of the classic Java applet.
2. It should show every step of the bubble‐sort algorithm for a vector of integers.
3. There should be two buttons on the page: Shuffle and Step.
    i. Shuffle button should initialize the array with a number of random integers within a configurable range.
    ii. Step button performs a single step of the sorting algorithm and displays it.
4. The array should be presented as a table with of rows (and not columns like in the applet). Each row would include a rectangle colored with a color of your choice. The width of the rectangle would be proportional to the number stored in the corresponding array cell.
5. Every time the Step button is pressed, two numbers are compared and possibly swapped. The rectangles representing these two numbers should be highlighted using colors different from the color of all the other rectangles.
6. Consecutive clicking on the step button should eventually sort the array so that the largest number (the widest rectangle) will be at the top of the page (the first row in the table) and the smallest number (the narrowest rectangle) will be in the last row of the table.
7. Once the array is sorted, the Step button should either be disabled or hidden or from the user. When the Shuffle button is clicked again, the Step button will be re-enabled.
8. All the processing needs to be done on the server side.
9. Make it as presentable and user‐friendly as possible.
10. Show the Bubble Sort demo on a dedicated path.
11. Make the module configurable – the admin may want to change the number of integers that need to be sorted, range of integers etc.
12. Figure out a way to make sure that the form saves the data from both authenticated and anonymous users in same system of storage.