# benchmark
An application for benchmarking website's load time in comparison to other websites.

The application runs in CLI mode. To use it you need to provide the tested website's URL as the first argument for the script and the competing website's URLs as the following arguments.

Example (testing `http://example.com` against `http://foo.net`, `http://bar.com` and `http://joe.co.uk`):

`php benchmark.php http://example.com http://foo.net http://bar.com http://joe.co.uk`

You need to provide the tested URL and at least one competitor URL to run the benchmark.
