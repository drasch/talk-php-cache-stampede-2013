# PHP Cache Storms 2013

## see slides
<pre>
$ gem install showoff
$ showoff serve
</pre>


## try demo
<pre>
$ cd demo
$ gem install vagrant
$ vagrant up #only need to do this once
$ vagrant ssh #this gets you an ssh session to your vm
</pre>

### run the app
monitoring the processlist
<pre>
vagrant:$ watch -n.5 'mysql -u root -A -e "show processlist"  | cut -f 6,7,8 | grep -v NULL' 
</pre>



### running the tests and generating load
<pre>
vagrant:$ httping -g http://0:80/index.php/v1
# in a separate window
vagrant:$ ab -c 5 -n 300 http://0:80/index.php/v1
</pre>

watch the timings, try again with v1 through v5

### versions
1. *v1* - showing some data, tricky query
2. *v2* - add hit counters
3. *v3* - add batching
4. *v4* - add caching 
4. *v5* - fix cache stampede



## destroying the vm
<pre>
$ vagrant destroy
</pre>`
