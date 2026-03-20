#!/usr/bin/perl
use strict;
use warnings;

my @arrsudo = ();

open(my $fh, "-|", "/usr/bin/journalctl", "-q", "-t", "sudo", "--no-pager", "--output=cat")
    or do { print "0"; exit 0; };

while (my $line = <$fh>) {
    if ($line =~ /\bCOMMAND=/) {
        push @arrsudo, $line;
    }
}

close($fh);

print scalar(@arrsudo);
