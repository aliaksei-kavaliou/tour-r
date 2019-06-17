Answers
==
- Parallel import. Since php does not naturally 
support multithreading, an implementation could be quite complicated 
as well as process control. I’m not sure how important for you to do 
import concurrently I would use a queue for import. It is easy to 
implement and maintain. Also, we could implement a retry mechanism for 
example.

- Storage. There is no information about storage, but if server disk is 
used to save tour files I would say there could be a problem. Disk space 
is limited. I would use aws s3 for that.  Also, s3 can push notification 
on sqs service on a new file added.


