package com.example.stream;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;
import com.example.stream.ui.home.HomeFragment;
import com.example.stream.ui.movies.MoviesFragment;
import com.example.stream.ui.series.SeriesFragment;
import com.example.stream.ui.live.LiveFragment;
import com.example.stream.ui.watchlater.WatchLaterFragment;

public class TabsAdapter extends FragmentStateAdapter {
    public TabsAdapter(@NonNull FragmentActivity fragmentActivity) {
        super(fragmentActivity);
    }

    @NonNull
    @Override
    public Fragment createFragment(int position) {
        switch (position) {
            case 0: return new HomeFragment();
            case 1: return MoviesFragment.newInstance();
            case 2: return SeriesFragment.newInstance();
            case 3: return LiveFragment.newInstance();
            default: return WatchLaterFragment.newInstance();
        }
    }

    @Override
    public int getItemCount() {
        return 5;
    }
}
