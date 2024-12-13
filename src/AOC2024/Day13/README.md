# The math

Watch out! Pseudo math incoming. Thanks to my lovely wife for explaining this
to my simple mind.

Given:

| **Button**   | **Expression**       |
|--------------|----------------------|
| Button A     | $`X + a_1, Y + a_2`$ |
| Button B     | $`X + b_1, Y + b_2`$ |
| Prize        | $`X = c_1, Y = c_2`$ |

We can state that to get value $`c_1`$ (our x coordinate of our prize) we have 
the sum of x times $`a_1`$ and y times $`b_1`$.

We could represent this as:

$$
a_1x + b_1y = c_1
$$

Similarly, we can state that to get value $`c_2`$ (our y coordinate of our 
prize) we have the sum of x times $`a_2`$ and y times $`b_2`$.

We could represent this as:

$$
a_2x + b_2y = c_2
$$

This gives us following system of linear equations:

$$
\begin{aligned}
a_1x + b_1y = c_1 \\
a_2x + b_2y = c_2
\end{aligned}
$$

We can first check if the two equations have the same slope 
("richtingscoëfficiënt"), if they do: check if the prize point is on that slope.

If it isn't, there is no possible solution, and the prize cannot be reached with
these buttons.

If it is, divide the prize's $`x`$ (so $`c_1`$) by the buttons $`\Delta x`$
(so $`a_1`$) and multiply it by the button's cost. Lowest number is the most 
efficient one.

If the slopes are different, we have exactly one possible answer, so the 
button's cost doens't matter anyway.

To solve this, we can create following matrix:

$$
\begin{bmatrix}
a_1 & b_1 & c_1\\
a_2 & b_2 & c_2
\end{bmatrix}
$$

We solve this using the method that _I think_ is called Cramer's rule.

First we multiply the first row of the matrix by the first element of the
second row of the matrix, and the second row of the matrix with the first
element of the first row of the matrix, which would give us:

$$
\begin{bmatrix}
a_2a_1 & a_2b_1 & a_2c_1\\
a_1a_2 & a_1b_2 & a_1c_2
\end{bmatrix}
$$

Then... And I don't know _why_ this is true, but I'll just have to accept that
it is:

In this system of linear equations:

$$
\begin{aligned}
a_1x + b_1y = c_1 \\
a_2x + b_2y = c_2 \\
\end{aligned}
$$

We can calculate $`y`$ using this forumla:

$$
\begin{aligned}
y = \frac{a_2c_1 - a_1c_2}{a_2b_1 - a_1b_2}
\end{aligned}
$$

Now, we can do the same for $`x`$. We multiply the first row of the matrix by
the first element of the second row of the matrix, and the second row of the 
matrix with the first element of the first row of the matrix, which would give
us:

$$
\begin{bmatrix}
b_2a_1 & b_2b_1 & b_2c_1\\
b_1a_2 & b_1b_2 & b_1c_2
\end{bmatrix}
$$


Then... And again: I don't know _why_ this is true, but I'll just have to accept
that it is:

In this system of linear equations:

$$
\begin{aligned}
a_1x + b_1y = c_1 \\
a_2x + b_2y = c_2 \\
\end{aligned}
$$

We can calculate $`x`$ using this forumla:

$$
\begin{aligned}
x = \frac{b_2a_1 - b_1a_2}{b_2c_1 - b_1c_2}
\end{aligned}
$$

Now "all" I have to do is translate this info into an algorithm. Easy peasy
lemon squeezy.
